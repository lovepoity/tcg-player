<div class="user-content">
  <h2 class="user-content__title">Payment Methods</h2>
  <div class="load-content">
    <div class="user-data">
      <h3>Credit & Debit Cards</h3>
      <p class="payment-card__desc">We accept Mastercard and Visa</p>
      <div class="payment-card">
        <h3 class="payment-card__title">Add your first card:</h3>

        <div class="payment-card__form">
          <div class="payment-card__field">
            <p class="payment-card__label">Name on Card</p>
            <input placeholder="Ex: Monkey D. Luffy" type="text" name="card_name" class="payment-card__input user--input">
          </div>

          <div class="payment-card__field">
            <p class="payment-card__label">Card Number</p>
            <input placeholder="Ex: 1234 5678 9101 1234" type="text" name="card_number" class="payment-card__input user--input" maxlength="16">
          </div>

          <div class="payment-card__row">
            <div class="payment-card__field payment-card__field--half">
              <p class="payment-card__label">Expiration</p>
              <input type="text" name="card_expiry" class="payment-card__input user--input" placeholder="MM/YY" maxlength="5">
            </div>

            <div class="payment-card__field payment-card__field--half">
              <p class="payment-card__label">Security Code</p>
              <input placeholder="Ex: 123" type="text" name="card_cvv" class="payment-card__input user--input" maxlength="3">
            </div>
          </div>
          <button class="user--submit payment-card__add-btn">Add Card</button>
        </div>
        <br><br>
        <h3 class="payment-card__title">Add a PayPal Account</h3>
        <button class="payment-card__add-btn--paypal"><img src="/public/images/paypal.webp" alt="PayPal"></button>
      </div>
    </div>
  </div>
</div>