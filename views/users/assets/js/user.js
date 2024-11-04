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
                // Dispatch event after content is loaded
                document.dispatchEvent(new Event('contentLoaded'));
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
                
                // Load script order_detail.js nếu chưa có
                if (!document.querySelector('script[src="/views/users/assets/js/order_detail.js"]')) {
                    const script = document.createElement('script');
                    script.src = '/views/users/assets/js/order_detail.js';
                    script.onload = function() {
                        // Khởi tạo lại các event listeners sau khi script được load
                        if (typeof window.initializeOrderDetail === 'function') {
                            window.initializeOrderDetail();
                        }
                    };
                    document.body.appendChild(script);
                } else {
                    // Nếu script đã tồn tại, chỉ cần khởi tạo lại event listeners
                    if (typeof window.initializeOrderDetail === 'function') {
                        window.initializeOrderDetail();
                    }
                }
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

    // Thêm các hàm xử lý email preferences
    window.saveSubscriptions = function() {
        const form = document.querySelector('.load--content');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        const data = {};

        checkboxes.forEach(checkbox => {
            const name = checkbox.name.match(/\[(.*?)\]/)[1];
            data[name] = checkbox.checked ? 1 : 0;
        });

        fetch('/views/users/functions/email_preferences.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Email preferences saved successfully!');
            } else {
                showToast('Error saving preferences');
            }
        })
        .catch(error => {
            showToast('Error saving preferences');
        });
    }

    window.unsubscribeAll = function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const saveButton = document.querySelector('.btn--change');
        const unsubscribeButton = document.querySelector('.btn--unsubscribe');
        
        // Bỏ check tất cả checkbox
        checkboxes.forEach(cb => cb.checked = false);
        
        // Cập nhật trạng thái buttons ngay lập tức
        saveButton.disabled = true;
        unsubscribeButton.disabled = true;
        saveButton.classList.remove('active');
        unsubscribeButton.classList.remove('active');
        
        // Lưu thay đổi
        window.saveSubscriptions();
    }

    // Xử lý email preferences khi content được load
    document.addEventListener('contentLoaded', function() {
        const checkboxes = document.querySelectorAll('.email__block input[type="checkbox"], .email__block--2 input[type="checkbox"], .email__block--3 input[type="checkbox"]');
        const saveButton = document.querySelector('.btn--change');
        const unsubscribeButton = document.querySelector('.btn--unsubscribe');
        
        if(saveButton && unsubscribeButton) {
            saveButton.disabled = true;
            unsubscribeButton.disabled = true;
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
                    saveButton.disabled = !hasChecked;
                    unsubscribeButton.disabled = !hasChecked;
                    saveButton.classList.toggle('active', hasChecked);
                    unsubscribeButton.classList.toggle('active', hasChecked);
                });
            });

            // Kiểm tra trạng thái ban đầu
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
            saveButton.disabled = !hasChecked;
            unsubscribeButton.disabled = !hasChecked;
            saveButton.classList.toggle('active', hasChecked);
            unsubscribeButton.classList.toggle('active', hasChecked);
        }

        const giftcardInput = document.querySelector('.giftcard-input');
        const redeemButton = document.querySelector('.giftcard-redeem');
        
        if (giftcardInput && redeemButton) {
            redeemButton.addEventListener('click', function() {
                const code = giftcardInput.value.trim();
                
                if (!code) {
                    showToast('Please enter a gift card code');
                    return;
                }

                // Giả lập response thành công
                showToast('Gift card redeemed successfully!');
                giftcardInput.value = '';
            });

            // Thêm xử lý khi người dùng nhấn Enter
            giftcardInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    redeemButton.click();
                }
            });
        }
    });

    // Thêm xử lý cho my__action clicks
    document.addEventListener('click', function(e) {
        const actionLink = e.target.closest('.my__action a');
        if (actionLink) {
            e.preventDefault();
            const tab = actionLink.dataset.tab;
            
            if (tab === 'user_history') {
                history.pushState({}, '', `${window.location.pathname}?tabs=orders`);
                loadOrderHistory();
            } else {
                history.pushState({}, '', `${window.location.pathname}?tabs=${tab}`);
                loadContent(tab);
            }
            
            updateActiveTab(tab);
        }
    });
});
