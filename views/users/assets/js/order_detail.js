function initializeOrderDetail() {
    const cancelBtn = document.querySelector('.order-detail__cancel-btn');
    const modal = document.getElementById('cancelOrderModal');
    
    if (!cancelBtn || !modal) return;
    
    const confirmBtn = modal.querySelector('.modal__btn--confirm');
    const cancelModalBtn = modal.querySelector('.modal__btn--cancel');
    const toast = document.getElementById('user__toast');
    const toastMessage = document.getElementById('user__toast-message');

    // Hiển thị modal
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
    });

    // Đóng modal khi click nút No
    cancelModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Đóng modal khi click bên ngoài
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Xử lý khi xác nhận hủy đơn
    confirmBtn.addEventListener('click', function() {
        const orderId = cancelBtn.dataset.orderId;
        const formData = new FormData();
        formData.append('order_id', orderId);
        
        fetch('/views/users/functions/cancel_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastMessage.textContent = 'Order cancelled successfully';
                // Cập nhật UI trực tiếp
                const statusElement = document.querySelector('.order__status--active');
                if (statusElement) {
                    statusElement.textContent = 'Cancelled';
                    statusElement.dataset.status = 'Cancelled';
                }
                
                // Ẩn nút cancel
                cancelBtn.style.display = 'none';
                
                toast.classList.add('show');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                toastMessage.textContent = data.error || 'Failed to cancel order';
                toast.classList.add('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastMessage.textContent = 'Error cancelling order';
            toast.classList.add('show');
        })
        .finally(() => {
            modal.style.display = 'none';
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        });
    });
}

// Gọi hàm khi DOM loaded
document.addEventListener('DOMContentLoaded', initializeOrderDetail);

// Export hàm để có thể gọi từ bên ngoài
window.initializeOrderDetail = initializeOrderDetail;