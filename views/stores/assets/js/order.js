$(document).ready(function () {
  loadOrderTable();

  // Event Listeners
  $('#statusFilter, #startDate, #endDate').on('change', loadOrderTable);
  $('#searchOrder').on('input', loadOrderTable);

  // Modal Events
  $(document).on('click', '.store-orders__btn--view', function () {
    const orderId = $(this).data('order-id');
    openOrderModal(orderId);
  });

  $('.order-modal__close').on('click', closeOrderModal);

  $(window).on('click', function (e) {
    if ($(e.target).is('#order-modal')) {
      closeOrderModal();
    }
  });
});

function loadOrderTable() {
  const currentFilters = {
    status: $('#statusFilter').val(),
    start_date: $('#startDate').val(),
    end_date: $('#endDate').val(),
    search: $('#searchOrder').val()
  };

  $.ajax({
    url: '/views/stores/actions/get_orders.php',
    method: 'GET',
    data: currentFilters,
    success: function (response) {
      $('#orderTableContainer').html(response);
    },
    error: function () {
      showToast('Error loading orders');
    }
  });
}

function updateOrderStatus(orderId) {
  const newStatus = $(`#status-${orderId}`).val();
  if (!newStatus) {
    showToast('Please select a status');
    return;
  }

  $.ajax({
    url: '/views/stores/actions/update_order_status.php',
    method: 'POST',
    data: {
      order_id: orderId,
      status: newStatus
    },
    success: function (response) {
      try {
        const result = JSON.parse(response);
        showToast(result.message);
        if (result.success) {
          const statusCell = $(`#status-${orderId}`).closest('tr').find('td:nth-child(4)');
          statusCell.html(`<span class="store-orders__status store-orders__status--${newStatus.toLowerCase()}">${newStatus}</span>`);
          $(`#status-${orderId}`).val(newStatus);

          if (result.order_status) {
            const orderStatusCell = $(`#order-status-${orderId}`);
            if (orderStatusCell.length) {
              orderStatusCell.html(`<span class="store-orders__status store-orders__status--${result.order_status.toLowerCase()}">${result.order_status}</span>`);
            }
          }
        }
      } catch (e) {
        showToast('Error updating order status');
      }
    },
    error: function () {
      showToast('Error updating order status');
    }
  });
}

function openOrderModal(orderId) {
  $('#order-modal').show();
  $.ajax({
    url: '/views/stores/actions/get_order_detail.php',
    method: 'GET',
    data: { order_id: orderId },
    success: function (response) {
      $('#order-modal__body').html(response);
    },
    error: function () {
      showToast('Error loading order details');
      closeOrderModal();
    }
  });
}

function closeOrderModal() {
  $('#order-modal').hide();
  $('#order-modal__body').html('');
}
