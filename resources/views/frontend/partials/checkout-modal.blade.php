@php
  $paySet = \App\Models\PaymentSetting::firstOrCreate([]);
@endphp

<div class="chk-modal" id="checkoutModal">
  <div class="chk-modal-bg" onclick="closeCheckoutModal()"></div>
  <div class="chk-modal-box">

    <div class="chk-modal-head">
      <h3>Checkout</h3>
      <button class="chk-modal-close" onclick="closeCheckoutModal()">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="chk-modal-body" id="chkModalBody">

      {{-- Order Summary --}}
      <div class="chk-m-summary" id="chkSummary"></div>

      <form id="checkoutForm" onsubmit="submitCheckout(event)">

        <label class="chk-label">Phone Number *</label>
        <input type="tel" id="chkPhone" class="chk-input" maxlength="10"
               inputmode="numeric" placeholder="10 digit mobile number" required>

        <label class="chk-label">Pincode *</label>
        <input type="text" id="chkPincode" class="chk-input" maxlength="6"
               inputmode="numeric" placeholder="6 digit pincode" required>
        <div id="pinStatus" class="pin-status"></div>

        <div class="chk-row">
          <input type="text" id="chkCity" class="chk-input" placeholder="City" readonly>
          <input type="text" id="chkState" class="chk-input" placeholder="State" readonly>
        </div>

        <label class="chk-label">Address *</label>
        <textarea id="chkAddress" class="chk-input" rows="2"
                  placeholder="House no, building, street" required></textarea>

        <label class="chk-label">Landmark (optional)</label>
        <input type="text" id="chkLocality" class="chk-input" placeholder="Landmark / Area">

        <label class="chk-label">Full Name *</label>
        <input type="text" id="chkName" class="chk-input"
               placeholder="Your full name" required>

        <label class="chk-label">Email (optional)</label>
        <input type="email" id="chkEmail" class="chk-input"
               placeholder="you@example.com">

        <label class="chk-label">Payment Method</label>
        <div class="pay-options" id="payOptions">
          @if($paySet->razorpay_enabled)
          <label class="pay-option active">
            <input type="radio" name="payment_method" value="razorpay" checked>
            <span>💳 Pay Online (UPI / Card / Netbanking)</span>
          </label>
          @endif
          @if($paySet->cod_enabled)
          <label class="pay-option {{ !$paySet->razorpay_enabled ? 'active' : '' }}">
            <input type="radio" name="payment_method" value="cod"
                   {{ !$paySet->razorpay_enabled ? 'checked' : '' }}>
            <span>💵 Cash on Delivery</span>
          </label>
          @endif
        </div>

        <button type="submit" class="btn-place" id="placeOrderBtn">
          Place Order
        </button>

      </form>
    </div>
  </div>
</div>

{{-- Razorpay SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Payment settings from PHP
const CHK_SHIPPING       = {{ $paySet->shipping_charge }};
const CHK_FREE_ABOVE     = {{ $paySet->free_shipping_above }};
const CHK_CREATE_URL     = "{{ route('checkout.create') }}";
const CHK_VERIFY_URL     = "{{ route('checkout.verify') }}";
const CHK_SUCCESS_BASE   = "{{ url('/checkout/success') }}";
</script>