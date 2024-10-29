document.addEventListener('DOMContentLoaded', function() {
    const userContent = document.getElementById('userContent');
    const navLinks = document.querySelectorAll('.user-nav__link');
    
    const urlParams = new URLSearchParams(window.location.search);
    const currentTab = urlParams.get('tabs') || 'my_account';
    const orderId = urlParams.get('order_id');
    
    // Initial load
    if (currentTab === 'orders' && orderId) {
        loadOrderDetail(orderId);
        updateActiveTab('orders');
    } else if (currentTab === 'orders') {
        loadOrderHistory();
        updateActiveTab('orders');
    } else {
        loadContent(currentTab);
        updateActiveTab(currentTab);
    }

    // Handle sidebar menu clicks
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.dataset.tab;
            
            if (tab === 'user_history') {
                history.pushState({}, '', `${window.location.pathname}?tabs=orders`);
                loadOrderHistory();
            } else {
                history.pushState({}, '', `${window.location.pathname}?tabs=${tab}`);
                loadContent(tab);
            }
            
            updateActiveTab(tab);
        });
    });

    // Handle order detail clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('order__total--btn-item')) {
            const orderId = e.target.dataset.orderId;
            loadOrderDetail(orderId);
            history.pushState({ orderId }, '', `${window.location.pathname}?tabs=orders&order_id=${orderId}`);
            updateActiveTab('orders');
        }

        if (e.target.classList.contains('order-detail__back-btn')) {
            loadOrderHistory();
            history.pushState({}, '', `${window.location.pathname}?tabs=orders`);
            updateActiveTab('orders');
        }
    });

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        const tab = new URLSearchParams(window.location.search).get('tabs') || 'my_account';
        const orderId = new URLSearchParams(window.location.search).get('order_id');
        
        if (tab === 'orders' && orderId) {
            loadOrderDetail(orderId);
        } else if (tab === 'orders') {
            loadOrderHistory();
        } else {
            loadContent(tab);
        }
        
        updateActiveTab(tab === 'orders' ? 'user_history' : tab);
    });

    function updateActiveTab(tab) {
        navLinks.forEach(link => {
            link.classList.remove('user-nav__link--active');
            if ((tab === 'orders' && link.dataset.tab === 'user_history') || 
                link.dataset.tab === tab) {
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

    function loadOrderDetail(orderId) {
        fetch(`/views/users/tabs/order_detail.php?order_id=${orderId}`)
            .then(response => response.text())
            .then(html => {
                userContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function loadOrderHistory() {
        fetch('/views/users/tabs/user_history.php')
            .then(response => response.text())
            .then(html => {
                userContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});
