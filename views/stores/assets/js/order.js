$(document).ready(function() {
  // Load initial orders
  loadOrders();

  // Filter change handlers
  $('#statusFilter, #startDate, #endDate').on('change', function() {
    loadOrders();
  });

  // Search input handler
  let searchTimeout;
  $('#orderSearch').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadOrders();
    }, 300);
  });

  // Load orders function
  function loadOrders() {
    const status = $('#statusFilter').val();
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    const search = $('#orderSearch').val();

    $.ajax({
      url: '/views/stores/tabs/order_table.php',
      method: 'GET',
      data: {
        status: status,
        start_date: startDate,
        end_date: endDate,
        search: search
      },
      success: function(response) {
        console.log('Response received:', response);
        $('#orderTableContainer').html(response);
      },
      error: function(xhr, status, error) {
        console.error('Error details:', {
          status: status,
          error: error,
          response: xhr.responseText
        });
        $('#orderTableContainer').html(
          '<p class="store-orders__error">Error loading orders. Check console for details.</p>'
        );
      }
    });
  }

  // Update order status
  $(document).on('click', '.store-orders__btn--update', function() {
    const orderId = $(this).data('order-id');
    const newStatus = $(this).data('status');

    $.ajax({
      url: '../actions/update_order_status.php',
      method: 'POST',
      data: {
        order_id: orderId,
        status: newStatus
      },
      success: function(response) {
        if(response.success) {
          showToast('Order status updated successfully');
          loadOrders();
        } else {
          showToast('Error updating order status', 'error');
        }
      }
    });
  });

  // View order details
  $(document).on('click', '.store-orders__btn--view', function() {
    const orderId = $(this).data('order-id');
    
    $.ajax({
      url: '../actions/get_order_details.php',
      method: 'GET',
      data: {
        order_id: orderId
      },
      success: function(response) {
        $('#orderDetailsContent').html(response);
        $('#orderDetailsModal').show();
      }
    });
  });

  // Close modal
  $('.store-modal__close').click(function() {
    $('#orderDetailsModal').hide();
  });

  // Close modal when clicking outside
  $(window).click(function(event) {
    if ($(event.target).is('#orderDetailsModal')) {
      $('#orderDetailsModal').hide();
    }
  });

  // Toast message function
  function showToast(message, type = 'success') {
    const toast = $('#store__toast');
    const toastMessage = $('#store__toast-message');
    
    toastMessage.text(message);
    toast.addClass(type === 'success' ? 'success' : 'error');
    toast.fadeIn();
    
    setTimeout(() => {
      toast.fadeOut();
      toast.removeClass('success error');
    }, 3000);
  }
});
