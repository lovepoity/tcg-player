document.addEventListener('DOMContentLoaded', function() {
    const userContent = document.getElementById('userContent');
    const navLinks = document.querySelectorAll('.user-nav__link');
    
    // Lấy tab từ URL hoặc dùng mặc định 'my_account'
    const currentTab = new URLSearchParams(window.location.search).get('tabs') || 'my_account';
    
    // Load tab hiện tại và cập nhật active state
    loadContent(currentTab);
    updateActiveTab(currentTab);

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.dataset.tab;
            
            // Cập nhật URL với tab mới
            const newUrl = `${window.location.pathname}?tabs=${tab}`;
            history.pushState({}, '', newUrl);
            
            updateActiveTab(tab);
            loadContent(tab);
        });
    });

    function updateActiveTab(tab) {
        navLinks.forEach(link => {
            link.classList.remove('user-nav__link--active');
            if (link.dataset.tab === tab) {
                link.classList.add('user-nav__link--active');
            }
        });
    }

    function loadContent(tab) {
        fetch(`./tabs/${tab}.php`)
            .then(response => response.text())
            .then(html => {
                userContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading content:', error);
                userContent.innerHTML = 'Error loading content';
            });
    }

    // Xử lý nút Back/Forward của trình duyệt
    window.addEventListener('popstate', function() {
        const tab = new URLSearchParams(window.location.search).get('tabs') || 'my_account';
        updateActiveTab(tab);
        loadContent(tab);
    });
});
