<?php
include '../../includes/header.php';
?>
<div class="container">
  <div class="cart">
    <div class="shopping__cart">
      <div class="cart__container">
        <h1>Shopping Cart</h1>
        <figure>
          <img src="/public/images/img__empty-cart.svg" alt="Empty Cart">
          <figcaption>Your cart is empty.</figcaption>
        </figure>
        <p>Sign in to see items from a previous visit.</p>
        <div class="btns__container">
          <button class="btn__signup"><a href="/views/login/sign_up.php">Sign Up Now</a></button>
          <button class="btn__signin"><a href="/views/login/sign_in.php">Sign In</a></button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include '../../includes/footer.php';
?>