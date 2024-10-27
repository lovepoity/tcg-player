document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    const paypalButtonContainer = document.getElementById('paypal-button-container');
    console.log('PayPal button container:', paypalButtonContainer);

    if (paypalButtonContainer) {
        console.log('Rendering PayPal button');
        paypal.Buttons({
            style: {
                layout: 'horizontal'
            },
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
                    return fetch('/views/cart/process_direct_payment.php', {
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
                            window.showToast('An error occurred during payment processing: ' + result.error);
                        }
                    })
                    .catch(error => {
                        window.showToast('An error occurred during payment processing');
                    });
                });
            }
        }).render('#paypal-button-container');
    } else {
        console.error('PayPal button container not found');
        console.log('All elements with IDs:', document.querySelectorAll('[id]'));
        window.showToast('PayPal button container not found. Please refresh the page and try again.');
    }
});
