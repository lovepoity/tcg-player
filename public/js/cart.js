$(document).ready(function() {
  function showToast(message, duration = 7000) {
    const $toast = $('#toast');
    $('#toast-message').text(message);
    
    $toast.removeClass('show');
    $toast[0].offsetHeight;
    $toast.addClass('show');
    
    setTimeout(function() {
      $toast.removeClass('show');
    }, duration);
  }

  // Cập nhật số lượng
  $('.quantity-select').change(function() {
    var cartId = $(this).data('cart-id');
    var newQuantity = $(this).val();
    var $package = $(this).closest('.package-tab');

    $.ajax({
      url: '/api/update_cart.php',
      method: 'POST',
      data: {
        cart_id: cartId,
        quantity: newQuantity
      },
      success: function(response) {
        if (response.success) {
          updatePackageSummary($package);
          updateCartSummary();
          showToast('Quantity updated successfully');
          updateCartCount(response.unique_items_count);
        } else {
          showToast('Cannot update quantity: ' + response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        showToast('An error occurred while updating the quantity. Please check the console for details.');
      }
    });
  });

  function updateCartSummary() {
    $.ajax({
      url: '/api/get_cart_summary.php',
      method: 'GET',
      success: function(response) {
        if (response.success) {
          if (response.total_items === 0) {
            location.reload();
          } else {
            $('.item__breakdown-item:nth-child(1) p:last-child').text(response.total_packages);
            $('.item__breakdown-item:nth-child(2) p:last-child').text(response.total_items);
            $('.item__breakdown-item:nth-child(3) p:last-child').text('$' + response.subtotal.toFixed(2));
            $('.item__breakdown-item:nth-child(4) p:last-child').text('$' + response.total_shipping.toFixed(2));
            $('.cart__subtotal p span').text('$' + response.grand_total.toFixed(2));
          }
        }
      }
    });
  }

  function updatePackageSummary($package) {
    var $items = $package.find('.package-tab__content-wrapper');
    var itemCount = 0;
    var subtotal = 0;

    $items.each(function() {
      var price = parseFloat($(this).find('.package-tab__item-sales-info-price').text().replace('$', ''));
      var quantity = parseInt($(this).find('.quantity-select').val());
      itemCount += quantity;
      subtotal += price * quantity;
    });

    $package.find('.package-subtotal').text('$' + subtotal.toFixed(2));
    $package.find('.package-item-count').text(itemCount);
    $package.find('.package-total').text('$' + subtotal.toFixed(2));

    if (itemCount === 0) {
      $package.remove();
      updateCartSummary();
    }
  }

  // Xóa sản phẩm
  $(document).on('click', '.remove-item', function() {
    var cartId = $(this).data('cart-id');
    var $item = $(this).closest('.package-tab__content-wrapper');
    var $package = $item.closest('.package-tab');

    $.ajax({
      url: '/api/remove_from_cart.php',
      method: 'POST',
      data: {
        cart_id: cartId
      },
      success: function(response) {
        if (response.success) {
          $item.remove();
          updatePackageSummary($package);
          updateCartSummary();
          showToast('Item removed from cart');
          updateCartCount(response.unique_items_count);
        } else {
          showToast('Cannot remove item: ' + response.message);
        }
      },
      error: function() {
        showToast('An error occurred while removing the item.');
      }
    });
  });

  // Xóa toàn bộ giỏ hàng
  $('.clear__cart').click(function() {
    $.ajax({
      url: '/api/clear_cart.php',
      method: 'POST',
      success: function(response) {
        if (response.success) {
          location.reload();
          showToast('Cart cleared successfully');
        } else {
          showToast('Cannot clear cart: ' + response.message);
        }
      },
      error: function() {
        showToast('An error occurred while clearing the cart.');
      }
    });
  });

  // Remove Package button
  $(document).on('click', '.remove-package', function() {
    var storeId = $(this).data('store-id');
    var $package = $(this).closest('.package-tab');

    $.ajax({
      url: '/api/remove_package.php',
      method: 'POST',
      data: { store_id: storeId },
      success: function(response) {
        if (response.success) {
          $package.remove();
          updateCartSummary();
          showToast('Package removed successfully');
          updateCartCount(response.unique_items_count);
        } else {
          showToast('Failed to remove package: ' + response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        showToast('An error occurred while removing the package. Please check the console for details.');
      }
    });
  });

  // Cập nhật hàm updateCartCount
  function updateCartCount(count) {
    $('#cart-count').text(count);
  }
});
