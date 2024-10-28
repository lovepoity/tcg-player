document.addEventListener('DOMContentLoaded', function() {
  const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
  function showToast(message, duration = 4000) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    // Reset the toast animation
    toast.classList.remove('show');
    void toast.offsetWidth; // Trigger reflow to restart the animation

    toastMessage.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
      toast.classList.remove('show');
    }, duration);
  }

  function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
      cartCountElement.textContent = count;
    }
  }

  addToCartButtons.forEach(button => {
      button.addEventListener('click', function(e) {
      e.preventDefault();
      const listingId = this.dataset.listingId;
      const quantitySelect = document.getElementById(`quantity_${listingId}`);
      const quantity = quantitySelect.value;

      addToCart(listingId, quantity);
    });
  });

  function addToCart(listingId, quantity) {
    fetch('/api/add_to_cart.php', {
      method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                listing_id: listingId,
                quantity: quantity
              })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                showToast('1 items added to cart.');
                updateRemainingQuantity(listingId, data.remaining_quantity);
                updateCartCount(data.unique_items_count); // Thêm dòng này
              } else {
                showToast(' ' + data.message);
                if (data.available_quantity !== undefined) {
                  updateRemainingQuantity(listingId, data.available_quantity);
                }
              }
            })
            .catch(error => {
              showToast('An error occurred while adding the item to cart.');
            });
        }

        function updateRemainingQuantity(listingId, remainingQuantity) {
          const quantitySelect = document.getElementById(`quantity_${listingId}`);
          const itemListing = quantitySelect.nextElementSibling;
          const currentValue = parseInt(quantitySelect.value);

          itemListing.textContent = `of ${remainingQuantity}`;

          quantitySelect.innerHTML = '';
          for (let i = 1; i <= Math.min(remainingQuantity, 100); i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            if (i === currentValue) {
              option.selected = true;
            }
            quantitySelect.appendChild(option);
          }

          if (currentValue > remainingQuantity) {
            quantitySelect.value = remainingQuantity;
          }

          const addToCartBtn = quantitySelect.nextElementSibling.nextElementSibling;
          if (remainingQuantity === 0) {
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'Out of Stock';
          } else {
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = 'Add to Cart';
          }
        }

        document.getElementById('sort').addEventListener('change', function() {
          const sortValue = this.value;
          const cardId = new URLSearchParams(window.location.search).get('id');
          
          fetch(`/api/sort_listings.php?id=${cardId}&sort=${sortValue}`)
            .then(response => response.json())
            .then(data => {
              const listingContainer = document.querySelector('.card__detail-listing');
              
              // Cập nhật HTML listings
              let listingsHTML = '';
              data.forEach(listing => {
                listingsHTML += `
                  <div class="listing__store">
                    <div class="listing__store-name listing__store-item">${listing.store_name} 
                      <span><i class="fa-solid fa-star"></i> 99.9% (10000+ Sales)</span>
                    </div>
                    <div class="listing__store-info listing__store-item">
                      <p>${listing.condition || 'Near Mint Foil'}</p>
                      <div class="listing__store-info-price">$${Number(listing.price).toFixed(2)}</div>
                      <div class="listing__store-info-shipping">
                        ${listing.shipping_cost > 0 
                          ? `+ $${Number(listing.shipping_cost).toFixed(2)} Shipping`
                          : 'Free Shipping'
                        }
                      </div>
                    </div>
                    <div class="listing__store-quantity listing__store-item">
                      <select name="quantity" id="quantity_${listing.id}">
                        ${[...Array(Math.min(listing.quantity, 100))].map((_, i) => 
                          `<option value="${i+1}">${i+1}</option>`
                        ).join('')}
                      </select>
                      <span class="item__listing">of ${listing.quantity}</span>
                      <button class="add-to-cart-btn" data-listing-id="${listing.id}">
                        Add to Cart
                      </button>
                    </div>
                  </div>
                `;
              });
              
              // Thay thế nội dung listings
              const listingsSection = listingContainer.querySelector('.listing__store');
              if (listingsSection) {
                listingsSection.innerHTML = listingsHTML;
              }
            })
            .catch(error => console.error('Error:', error));
        });
      });
