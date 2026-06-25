@extends('frontend.layout.app')

@section('title', $product->name . ' — Vardiyash')
@section('meta', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))

@section('content')

<style>
/* ════════ Product Detail Page ════════ */
.pd-wrap { max-width: 1400px; margin: 0 auto; padding: 20px 20px 60px; }

/* Breadcrumb */
.breadcrumb-row {
  display: flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: #888;
  margin-bottom: 18px; font-family: 'Inter', sans-serif;
  flex-wrap: wrap;
}
.breadcrumb-row a { color: #888; transition: color .2s; }
.breadcrumb-row a:hover { color: #111; }
.breadcrumb-row span { color: #111; font-weight: 600; }
.breadcrumb-sep { color: #ccc; }

/* Layout */
.pd-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: flex-start;
}

/* ── Gallery ── */
.pd-gallery {
  display: flex;
  gap: 12px;
  position: sticky;
  top: 90px;
}
.pd-thumbs {
  display: flex;
  flex-direction: column;
  gap: 8px;
  width: 70px;
  flex-shrink: 0;
}
.pd-thumb {
  width: 100%;
  aspect-ratio: 3/4;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  border: 1.5px solid transparent;
  background: #f5f5f5;
  transition: border-color .2s;
}
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pd-thumb.active { border-color: #111; }

.pd-main {
  flex: 1;
  border-radius: 6px;
  overflow: hidden;
  aspect-ratio: 3/4;
  background: #f5f5f5;
  position: relative;
}
.pd-main img {
  width: 100%; height: 100%;
  object-fit: cover; display: block;
}
.pd-main-badge {
  position: absolute;
  top: 14px; left: 14px;
  background: #7b1f2e; color: white;
  font-size: 11px; font-weight: 800;
  padding: 4px 10px; text-transform: uppercase;
  letter-spacing: .5px; font-family: 'Inter', sans-serif;
}

/* ── Info ── */
.pd-info { font-family: 'Inter', sans-serif; }

.pd-brand {
  font-size: 12px; font-weight: 600; color: #888;
  text-transform: uppercase; letter-spacing: 1.5px;
  margin-bottom: 6px;
}
.pd-title {
  font-size: 24px; font-weight: 800; color: #111;
  letter-spacing: -.3px; line-height: 1.3;
  margin: 0 0 10px;
}

.pd-rating {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: #333; margin-bottom: 14px;
}
.pd-rating .stars { color: #f5a623; font-size: 13px; }
.pd-rating .rcount { color: #888; }

.pd-price-row {
  display: flex; align-items: center; gap: 10px;
  flex-wrap: wrap; margin-bottom: 4px;
}
.pd-price-now { font-size: 26px; font-weight: 800; color: #111; }
.pd-price-was { font-size: 16px; color: #aaa; text-decoration: line-through; }
.pd-price-off { font-size: 13px; font-weight: 700; color: #7b1f2e; }
.pd-tax-note { font-size: 12px; color: #999; margin-bottom: 20px; }

.pd-stock-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; font-weight: 700;
  padding: 3px 10px; border-radius: 2px;
  margin-bottom: 18px;
}
.pd-stock-badge.in  { background: #e8f5e9; color: #2e7d32; }
.pd-stock-badge.out { background: #fdecea; color: #c62828; }

/* Section blocks */
.pd-section { margin-bottom: 22px; }
.pd-label {
  display: flex; align-items: center; justify-content: space-between;
  font-size: 12.5px; font-weight: 700; color: #111;
  text-transform: uppercase; letter-spacing: 1px;
  margin-bottom: 12px;
}
.pd-label .selected-val { font-weight: 500; text-transform: none; color: #666; letter-spacing: 0; }
.pd-label a { font-size: 12px; font-weight: 600; color: #7b1f2e; text-decoration: underline; }

/* Color swatches */
.pd-colors { display: flex; gap: 10px; flex-wrap: wrap; }
.pd-color-btn {
  width: 34px; height: 34px;
  border-radius: 50%;
  border: 2px solid transparent;
  outline: 2px solid transparent;
  outline-offset: 2px;
  cursor: pointer;
  transition: all .15s;
  position: relative;
}
.pd-color-btn:hover, .pd-color-btn.active { outline-color: #111; }
.pd-color-btn.disabled {
  opacity: .3; cursor: not-allowed;
}
.pd-color-btn.disabled::after {
  content: '';
  position: absolute; inset: -2px;
  background: linear-gradient(to bottom right, transparent 47%, #999 48%, #999 52%, transparent 53%);
  border-radius: 50%;
}

/* Size buttons */
.pd-sizes { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-size-btn {
  min-width: 52px; height: 44px;
  border: 1.5px solid #ddd; background: white;
  font-size: 13px; font-weight: 600; color: #333;
  padding: 0 14px; cursor: pointer;
  transition: all .15s; font-family: 'Inter', sans-serif;
}
.pd-size-btn:hover { border-color: #111; }
.pd-size-btn.active { background: #111; color: white; border-color: #111; }
.pd-size-btn.disabled {
  opacity: .35; text-decoration: line-through;
  cursor: not-allowed; background: #f5f5f5;
}
.pd-size-btn.disabled:hover { border-color: #ddd; }

/* Quantity */
.qty-row { display: flex; align-items: center; gap: 0; width: fit-content; }
.qty-btn {
  width: 38px; height: 40px;
  border: 1.5px solid #ddd; background: white;
  font-size: 16px; font-weight: 600; color: #333;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all .15s;
}
.qty-btn:hover { background: #f5f5f5; }
.qty-val {
  width: 50px; height: 40px;
  border-top: 1.5px solid #ddd; border-bottom: 1.5px solid #ddd;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif;
}

/* Action buttons */
.pd-actions { display: flex; gap: 12px; margin-bottom: 24px; }
.btn-pd {
  flex: 1; padding: 15px; font-size: 13px; font-weight: 800;
  text-transform: uppercase; letter-spacing: 1px;
  border: 1.5px solid #111; cursor: pointer;
  font-family: 'Inter', sans-serif; transition: all .2s;
}
.btn-add-cart { background: white; color: #111; }
.btn-add-cart:hover { background: #111; color: white; }
.btn-buy-now { background: #111; color: white; }
.btn-buy-now:hover { background: #7b1f2e; border-color: #7b1f2e; }
.btn-pd.disabled { opacity: .4; cursor: not-allowed; pointer-events: none; }

/* Delivery check */
.pd-delivery {
  border: 1px solid #eee; padding: 16px; margin-bottom: 24px;
}
.pd-delivery-title {
  font-size: 12.5px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 1px; color: #111; margin-bottom: 10px;
  display: flex; align-items: center; gap: 6px;
}
.pd-delivery-row { display: flex; gap: 8px; }
.pd-delivery-row input {
  flex: 1; border: 1.5px solid #ddd; padding: 10px 12px;
  font-size: 13px; outline: none; font-family: 'Inter', sans-serif;
}
.pd-delivery-row input:focus { border-color: #111; }
.pd-delivery-row button {
  background: #111; color: white; border: none;
  padding: 0 20px; font-size: 12.5px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .5px; cursor: pointer;
  font-family: 'Inter', sans-serif; transition: background .2s;
}
.pd-delivery-row button:hover { background: #7b1f2e; }
.pd-delivery-result {
  font-size: 12.5px; color: #2e7d32; margin-top: 10px;
  display: none; font-weight: 600;
}

/* Trust badges */
.pd-trust {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
  margin-bottom: 24px;
}
.pd-trust-item {
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  text-align: center; padding: 12px 6px;
  border: 1px solid #f0f0f0;
}
.pd-trust-item i { font-size: 20px; color: #7b1f2e; }
.pd-trust-item span { font-size: 11px; font-weight: 600; color: #333; line-height: 1.3; }

/* Accordion */
.pd-accordion { border-top: 1px solid #eee; }
.acc-item { border-bottom: 1px solid #eee; }
.acc-head {
  width: 100%; display: flex; align-items: center; justify-content: space-between;
  padding: 16px 0; background: none; border: none; cursor: pointer;
  font-size: 14px; font-weight: 700; color: #111;
  font-family: 'Inter', sans-serif; text-align: left;
}
.acc-head i { font-size: 14px; color: #888; transition: transform .2s; }
.acc-head.open i { transform: rotate(180deg); }
.acc-body {
  display: none; padding: 0 0 16px;
  font-size: 13.5px; color: #555; line-height: 1.8;
}
.acc-body.show { display: block; }

/* Related */
.pd-related { margin-top: 60px; }
.pd-related h2 {
  font-size: 20px; font-weight: 800; color: #111;
  font-family: 'Inter', sans-serif; margin-bottom: 20px;
  text-align: center;
}

/* Mobile gallery */
.pd-main-mobile { display: none; }

/* Responsive */
@media (max-width: 991px) {
  .pd-layout { grid-template-columns: 1fr; gap: 24px; }
  .pd-gallery { position: relative; top: 0; flex-direction: column-reverse; }
  .pd-thumbs {
    flex-direction: row; width: 100%; overflow-x: auto;
    scrollbar-width: none;
  }
  .pd-thumbs::-webkit-scrollbar { display: none; }
  .pd-thumb { width: 64px; flex-shrink: 0; }
  .pd-trust { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 575px) {
  .pd-wrap { padding: 14px 14px 50px; }
  .pd-title { font-size: 19px; }
  .pd-price-now { font-size: 22px; }
  .pd-actions { flex-direction: column; }
}
</style>

{{-- Breadcrumb --}}
<div class="pd-wrap" style="padding-bottom:0">
  <div class="breadcrumb-row">
    <a href="{{ route('home') }}">Home</a>
    <span class="breadcrumb-sep">›</span>
    @if($product->category?->parent)
      <a href="{{ route('collection.show', $product->category->parent->slug) }}">
        {{ $product->category->parent->name }}
      </a>
      <span class="breadcrumb-sep">›</span>
    @endif
    @if($product->category)
      <a href="{{ route('collection.show', $product->category->slug) }}">
        {{ $product->category->name }}
      </a>
      <span class="breadcrumb-sep">›</span>
    @endif
    <span>{{ $product->name }}</span>
  </div>
</div>

<div class="pd-wrap" style="padding-top:0">
  <div class="pd-layout">

    {{-- ══════ GALLERY ══════ --}}
    <div class="pd-gallery">

      @if($product->images->count() > 1)
      <div class="pd-thumbs" id="pdThumbs">
        @foreach($product->images as $img)
        <div class="pd-thumb {{ $loop->first ? 'active' : '' }}"
             onclick="pdSetImage('{{ $img->url }}', this)">
          <img src="{{ $img->url }}" alt="{{ $product->name }}">
        </div>
        @endforeach
      </div>
      @endif

      <div class="pd-main">
        @if($product->sale_price)
        <span class="pd-main-badge">-{{ $product->discount_percent }}% OFF</span>
        @endif

        @if($product->images->count())
          <img id="pdMainImage"
               src="{{ $product->images->first()->url }}"
               alt="{{ $product->name }}">
        @else
          <div style="width:100%;height:100%;background:#eee;
                      display:flex;align-items:center;justify-content:center">
            <i class="bi bi-image" style="font-size:3rem;color:#ccc"></i>
          </div>
        @endif
      </div>

    </div>

    {{-- ══════ INFO ══════ --}}
    <div class="pd-info">

      @if($product->brand)
      <div class="pd-brand">{{ $product->brand }}</div>
      @endif

      <h1 class="pd-title">{{ $product->name }}</h1>

      {{-- Rating placeholder --}}
      <div class="pd-rating">
        <span class="stars">
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-half"></i>
        </span>
        <span>4.8</span>
        <span class="rcount">| 24 Reviews</span>
      </div>

      {{-- Price --}}
      <div class="pd-price-row">
        <span class="pd-price-now" id="pdPrice">
          ₹{{ number_format($product->sale_price ?? $product->price) }}
        </span>
        @if($product->sale_price)
          <span class="pd-price-was">₹{{ number_format($product->price) }}</span>
          <span class="pd-price-off">{{ $product->discount_percent }}% OFF</span>
        @endif
      </div>
      <div class="pd-tax-note">Inclusive of all taxes</div>

      {{-- Stock badge --}}
      @if($product->total_stock > 0)
      <div class="pd-stock-badge in" id="pdStockBadge">
        <i class="bi bi-check-circle-fill"></i> In Stock
      </div>
      @else
      <div class="pd-stock-badge out" id="pdStockBadge">
        <i class="bi bi-x-circle-fill"></i> Out of Stock
      </div>
      @endif

      {{-- Color --}}
      @if($colors->count())
      <div class="pd-section">
        <div class="pd-label">
          Color
          <span class="selected-val" id="pdColorLabel">
            {{ $colors->first()->label ?? $colors->first()->value }}
          </span>
        </div>
        <div class="pd-colors" id="pdColors">
          @foreach($colors as $cl)
          <div class="pd-color-btn {{ $loop->first ? 'active' : '' }}"
               style="background:{{ $cl->meta ?? $cl->value }}"
               title="{{ $cl->label ?? $cl->value }}"
               data-color-id="{{ $cl->id }}"
               data-color-label="{{ $cl->label ?? $cl->value }}"
               onclick="pdSelectColor(this)">
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Size --}}
      @if($sizes->count())
      <div class="pd-section">
        <div class="pd-label">
          Size
          <a href="#">Size Guide</a>
        </div>
        <div class="pd-sizes" id="pdSizes">
          @foreach($sizes as $sz)
          <button type="button" class="pd-size-btn"
                  data-size-id="{{ $sz->id }}"
                  data-size-label="{{ $sz->value }}"
                  onclick="pdSelectSize(this)">
            {{ $sz->value }}
          </button>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Quantity --}}
      <div class="pd-section">
        <div class="pd-label">Quantity</div>
        <div class="qty-row">
          <button type="button" class="qty-btn" onclick="pdQty(-1)">−</button>
          <div class="qty-val" id="pdQtyVal">1</div>
          <button type="button" class="qty-btn" onclick="pdQty(1)">+</button>
        </div>
      </div>

      {{-- Actions --}}
      <div class="pd-actions">
        <button type="button" class="btn-pd btn-add-cart"
                id="pdAddCart" onclick="pdAddToCart('cart')">
          <i class="bi bi-bag-plus"></i> Add to Cart
        </button>
        <button type="button" class="btn-pd btn-buy-now"
                id="pdBuyNow" onclick="pdAddToCart('buy')">
          Buy Now
        </button>
      </div>

      {{-- Delivery check --}}
      <div class="pd-delivery">
        <div class="pd-delivery-title">
          <i class="bi bi-truck"></i> Check Delivery
        </div>
        <div class="pd-delivery-row">
          <input type="text" id="pdPincode" placeholder="Enter Pincode"
                 maxlength="6" inputmode="numeric">
          <button type="button" onclick="pdCheckDelivery()">Check</button>
        </div>
        <div class="pd-delivery-result" id="pdDeliveryResult"></div>
      </div>

      {{-- Trust badges --}}
      <div class="pd-trust">
        <div class="pd-trust-item">
          <i class="bi bi-truck"></i>
          <span>Free Shipping ₹999+</span>
        </div>
        <div class="pd-trust-item">
          <i class="bi bi-arrow-repeat"></i>
          <span>7 Day Easy Returns</span>
        </div>
        <div class="pd-trust-item">
          <i class="bi bi-shield-check"></i>
          <span>100% Original</span>
        </div>
      </div>

      {{-- Accordion --}}
      <div class="pd-accordion">

        <div class="acc-item">
          <button class="acc-head open" onclick="pdAccToggle(this)">
            Description <i class="bi bi-chevron-down"></i>
          </button>
          <div class="acc-body show">
            {{ $product->description ?: 'No description available.' }}
          </div>
        </div>

        <div class="acc-item">
          <button class="acc-head" onclick="pdAccToggle(this)">
            Size &amp; Fit <i class="bi bi-chevron-down"></i>
          </button>
          <div class="acc-body">
            This product follows standard Indian sizing. For the best fit,
            check the size guide above. If between sizes, we recommend sizing up
            for a relaxed/oversized fit.
          </div>
        </div>

        <div class="acc-item">
          <button class="acc-head" onclick="pdAccToggle(this)">
            Shipping &amp; Returns <i class="bi bi-chevron-down"></i>
          </button>
          <div class="acc-body">
            Orders are dispatched within 1-2 business days. Free shipping on
            orders above ₹999. Easy 7-day returns and exchanges — items must be
            unused with original tags attached.
          </div>
        </div>

        @if($product->sku)
        <div class="acc-item">
          <button class="acc-head" onclick="pdAccToggle(this)">
            Product Details <i class="bi bi-chevron-down"></i>
          </button>
          <div class="acc-body">
            SKU: {{ $product->sku }}
            @if($product->brand)<br>Brand: {{ $product->brand }}@endif
          </div>
        </div>
        @endif

      </div>

    </div>
  </div>

  {{-- ══════ RELATED PRODUCTS ══════ --}}
  @if($relatedProducts->count())
  <div class="pd-related">
    <h2>You May Also Like</h2>
    <div class="prod-coll-grid">
      @foreach($relatedProducts as $related)
        @include('frontend.partials.product-card', ['product' => $related])
      @endforeach
    </div>
  </div>
  @endif

</div>

@endsection

@push('scripts')
<script>
const pdVariants = @json($variantsData);
const pdBasePrice = {{ $product->sale_price ?? $product->price }};
let pdSelectedSize  = null;
let pdSelectedColor = {{ $colors->count() ? $colors->first()->id : 'null' }};
let pdQtyCount = 1;
let pdMaxQty = 10;

/* ── Gallery ── */
function pdSetImage(src, el) {
  document.getElementById('pdMainImage').src = src;
  document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

/* ── Color select ── */
function pdSelectColor(el) {
  document.querySelectorAll('.pd-color-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  pdSelectedColor = parseInt(el.dataset.colorId);
  const lbl = document.getElementById('pdColorLabel');
  if (lbl) lbl.textContent = el.dataset.colorLabel;
  pdUpdateSizeAvailability();
  pdUpdateStockUI();
}

/* ── Size select ── */
function pdSelectSize(el) {
  if (el.classList.contains('disabled')) return;
  document.querySelectorAll('.pd-size-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  pdSelectedSize = parseInt(el.dataset.sizeId);
  pdUpdateStockUI();
}

/* ── Mark sizes out-of-stock based on selected color ── */
function pdUpdateSizeAvailability() {
  document.querySelectorAll('.pd-size-btn').forEach(btn => {
    const sizeId = parseInt(btn.dataset.sizeId);
    const variant = pdVariants.find(v =>
      v.size_id === sizeId &&
      (pdSelectedColor === null || v.color_id === pdSelectedColor)
    );
    const inStock = variant && variant.stock > 0;
    btn.classList.toggle('disabled', !inStock);
    if (!inStock && btn.classList.contains('active')) {
      btn.classList.remove('active');
      pdSelectedSize = null;
    }
  });
}

/* ── Update price + stock badge based on selection ── */
function pdUpdateStockUI() {
  const variant = pdVariants.find(v =>
    (pdSelectedSize === null || v.size_id === pdSelectedSize) &&
    (pdSelectedColor === null || v.color_id === pdSelectedColor)
  );

  const priceEl = document.getElementById('pdPrice');
  const badge   = document.getElementById('pdStockBadge');
  const addBtn  = document.getElementById('pdAddCart');
  const buyBtn  = document.getElementById('pdBuyNow');

  if (variant) {
    const price = pdBasePrice + (variant.extra_price || 0);
    if (priceEl) priceEl.textContent = '₹' + price.toLocaleString('en-IN');
    pdMaxQty = Math.min(variant.stock, 10);

    if (variant.stock > 0) {
      badge.className = 'pd-stock-badge in';
      badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> In Stock';
      addBtn?.classList.remove('disabled');
      buyBtn?.classList.remove('disabled');
    } else {
      badge.className = 'pd-stock-badge out';
      badge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Out of Stock';
      addBtn?.classList.add('disabled');
      buyBtn?.classList.add('disabled');
    }
  }

  // Reset qty if exceeds max
  if (pdQtyCount > pdMaxQty) {
    pdQtyCount = Math.max(1, pdMaxQty);
    document.getElementById('pdQtyVal').textContent = pdQtyCount;
  }
}

/* ── Quantity ── */
function pdQty(dir) {
  const next = pdQtyCount + dir;
  if (next < 1) return;
  if (next > pdMaxQty) {
    if (typeof toast === 'function') toast('Only ' + pdMaxQty + ' available');
    return;
  }
  pdQtyCount = next;
  document.getElementById('pdQtyVal').textContent = pdQtyCount;
}

/* ── Accordion ── */
function pdAccToggle(btn) {
  const body = btn.nextElementSibling;
  const isOpen = body.classList.contains('show');
  // Close all
  document.querySelectorAll('.acc-body').forEach(b => b.classList.remove('show'));
  document.querySelectorAll('.acc-head').forEach(h => h.classList.remove('open'));
  if (!isOpen) {
    body.classList.add('show');
    btn.classList.add('open');
  }
}

/* ── Delivery check (client-side estimate) ── */
function pdCheckDelivery() {
  const pin = document.getElementById('pdPincode').value.trim();
  const result = document.getElementById('pdDeliveryResult');
  if (!/^\d{6}$/.test(pin)) {
    if (typeof toast === 'function') toast('Enter a valid 6-digit pincode');
    return;
  }
  const date = new Date();
  date.setDate(date.getDate() + 5);
  const opts = { day: 'numeric', month: 'short' };
  result.textContent = '✓ Delivery by ' + date.toLocaleDateString('en-IN', opts) +
                        ' to ' + pin;
  result.style.display = 'block';
}

/* ── Add to Cart / Buy Now ── */
function pdAddToCart(mode) {
  const sizeRequired  = {{ $sizes->count() ? 'true' : 'false' }};
  const colorRequired = {{ $colors->count() ? 'true' : 'false' }};

  if (sizeRequired && pdSelectedSize === null) {
    if (typeof toast === 'function') toast('Please select a size');
    return;
  }
  if (colorRequired && pdSelectedColor === null) {
    if (typeof toast === 'function') toast('Please select a color');
    return;
  }

  const variant = pdVariants.find(v =>
    (pdSelectedSize  === null || v.size_id  === pdSelectedSize) &&
    (pdSelectedColor === null || v.color_id === pdSelectedColor)
  );

  if (variant && variant.stock <= 0) {
    if (typeof toast === 'function') toast('This item is out of stock');
    return;
  }

  const price = pdBasePrice + (variant?.extra_price || 0);

  // ✅ Size label + Color label properly set karo
  const sizeLabel  = document.querySelector('.pd-size-btn.active')?.textContent.trim() || '';
  const colorLabel = document.getElementById('pdColorLabel')?.textContent.trim() || '';

  const cartItem = {
    id:          {{ $product->id }},
    slug:        '{{ $product->slug }}',
    name:        @json($product->name),
    image:       '{{ $product->images->first()->url ?? "" }}',
    price:       price,
    size_id:     pdSelectedSize,
    color_id:    pdSelectedColor,
    size_label:  sizeLabel,
    color_label: colorLabel,
    qty:         pdQtyCount,
  };

  // ✅ BUY NOW — seedha checkout modal khulega
  if (mode === 'buy') {
    if (typeof openCheckoutModal === 'function') {
      openCheckoutModal([cartItem]);
    } else {
      console.error('openCheckoutModal not defined — check custom.js load order');
    }
    return;
  }

  // ✅ ADD TO CART — localStorage + drawer
  let cart = [];
  try { cart = JSON.parse(localStorage.getItem('cart') || '[]'); } catch(e) {}

  const existingIndex = cart.findIndex(i =>
    i.id === cartItem.id &&
    i.size_id  === cartItem.size_id &&
    i.color_id === cartItem.color_id
  );

  if (existingIndex > -1) {
    cart[existingIndex].qty += pdQtyCount;
  } else {
    cart.push(cartItem);
  }

  localStorage.setItem('cart', JSON.stringify(cart));
  if (typeof refreshCart === 'function') refreshCart();

  // ✅ Cart drawer khulega (page nahi badle)
  if (typeof openCartDrawer === 'function') {
    openCartDrawer();
  }
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', function() {
  pdUpdateSizeAvailability();
  pdUpdateStockUI();
});
</script>
@endpush