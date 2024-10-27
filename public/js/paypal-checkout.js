document.addEventListener('DOMContentLoaded', function() {
    const paypalCheckbox = document.getElementById('paypal-cbx');
    const submitOrderButton = document.getElementById('submit-order');
    const paypalButtonContainer = document.getElementById('paypal-button-container');

    paypalCheckbox.addEventListener('change', function() {
        if (this.checked) {
            submitOrderButton.style.display = 'none';
            paypalButtonContainer.style.display = 'block';
        } else {
            submitOrderButton.style.display = 'block';
            paypalButtonContainer.style.display = 'none';
        }
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: grandTotal.toFixed(2)
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                return fetch('/views/cart/process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        paymentMethod: 'paypal'
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.location.href = '/views/cart/order_confirmation.php?order_id=' + result.order_id;
                    } else {
                        showToast('An error occurred during payment processing: ' + result.error);
                    }
                })
                .catch(error => {
                    showToast('An error occurred during payment processing');
                });
            });
        }
    }).render('#paypal-button-container');
});
