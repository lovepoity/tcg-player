// Thêm đoạn này ở đầu file, ngoài $(document).ready
window.showToast = function(message, duration = 4000) {
    const $toast = $('#toast');
    $('#toast-message').text(message);
    
    $toast.removeClass('show');
    $toast[0].offsetHeight;
    $toast.addClass('show');
    
    setTimeout(function() {
      $toast.removeClass('show');
    }, duration);
}

$(document).ready(function() {
  function showToast(message, duration = 4000) {
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
        updatePackageSummary($package);
        updateCartSummary();
        showToast('Quantity updated successfully.');
        updateCartCount(response.unique_items_count);
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
            // Thay vì reload, hiển thị giỏ hàng trống
            showEmptyCart();
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

  function showEmptyCart() {
    $('.cart__container').html(`
      <h1>Shopping Cart</h1>
      <figure>
        <img src="/public/images/img__empty-cart.svg" alt="Empty Cart">
        <figcaption>Your cart is empty.</figcaption>
      </figure>
      <div class="btns__container">
        <a href="/views/card_all.php?show_all=1"><button class="btn__continue-shopping">Continue Shopping</button></a>
      </div>
    `);
    $('.cart__summary').hide();
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
  $(document).on('click', '.remove-item', function(e) {
    e.preventDefault();
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
          showToast('Item removed from cart.');
          updateCartCount(response.unique_items_count);
          
          // Kiểm tra nếu package trống, xóa package
          if ($package.find('.package-tab__content-wrapper').length === 0) {
            $package.remove();
          }
          
          // Kiểm tra nếu giỏ hàng trống, hiển thị giao diện giỏ hàng trống
          if (response.total_items === 0) {
            showEmptyCart();
          }
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
  $('.clear__cart').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: '/api/clear_cart.php',
      method: 'POST',
      success: function(response) {
        if (response.success) {
          showEmptyCart();
          updateCartCount(0);
          showToast('Cart cleared successfully.');
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
  $(document).on('click', '.remove-package', function(e) {
    e.preventDefault();
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
          showToast('Package removed successfully.');
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
