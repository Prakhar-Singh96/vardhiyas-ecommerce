<div class="cart-drawer" id="cartDrawer">
  <div class="cart-drawer-bg" onclick="closeCartDrawer()"></div>
  <div class="cart-drawer-panel">

    <div class="cd-head">
      <h3 id="cdTitle">Cart</h3>
      <button class="cd-close" onclick="closeCartDrawer()">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="cd-body" id="cdBody"></div>

    <div class="cd-foot" id="cdFoot" style="display:none">
      <div class="cd-foot-row">
        <span>Subtotal</span>
        <span id="cdSubtotal">₹0</span>
      </div>
      <button class="cd-checkout-btn" onclick="goCheckoutFromCart()">
        Checkout
      </button>
    </div>

  </div>
</div>