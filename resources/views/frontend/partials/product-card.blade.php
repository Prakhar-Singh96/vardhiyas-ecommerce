@php
  $sizes  = $product->variants->where('stock', '>', 0)
              ->map(fn($v) => $v->size)->filter()->unique('id');
  $colors = $product->variants->where('stock', '>', 0)
              ->map(fn($v) => $v->color)->filter()->unique('id');

  // JS ke liye full variants JSON
  $variantsJson = $product->variants->map(fn($v) => [
      'size_id'     => $v->size_id,
      'color_id'    => $v->color_id,
      'stock'       => $v->stock,
      'extra_price' => (float) $v->extra_price,
  ])->toJson();

  // ✅ NAYA — agar collection page se size/color filter pass hua hai
  $activeSizeId  = $activeSizeId  ?? null;
  $activeColorId = $activeColorId ?? null;

  $displayMrp  = $product->price;
  $displaySale = $product->sale_price ?? $product->price;
  $displayPct  = $product->discount_percent;

  if ($activeSizeId || $activeColorId) {
      $activeVariant = $product->variants->first(function ($v) use ($activeSizeId, $activeColorId) {
          return (!$activeSizeId  || $v->size_id  == $activeSizeId)
              && (!$activeColorId || $v->color_id == $activeColorId)
              && $v->stock > 0;
      });

      if ($activeVariant) {
          $displaySale = $displaySale + (float) $activeVariant->extra_price;
          if ($product->sale_price && $displayMrp > $displaySale) {
              $displayPct = round((($displayMrp - $displaySale) / $displayMrp) * 100);
          }
      }
  }
@endphp

<div class="p-card"
     data-mrp="{{ $product->price }}"
     data-sale="{{ $product->sale_price ?? '' }}"
     data-variants='{{ $variantsJson }}'>

  <a href="{{ route('product.show', $product->slug) }}" class="p-thumb">
    {{-- Badge --}}
    @if($product->sale_price)
      <span class="p-badge badge-off">-{{ $displayPct }}%</span>
    @else
      <span class="p-badge badge-nw">New</span>
    @endif

    <button class="p-wish" aria-label="Wishlist">
      <i class="bi bi-heart"></i>
    </button>

    @if($product->primaryImage)
      <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
           alt="{{ $product->name }}" loading="lazy">
    @else
      <div style="width:100%;height:100%;background:#eee;
                  display:flex;align-items:center;justify-content:center">
        <i class="bi bi-image" style="font-size:2rem;color:#ccc"></i>
      </div>
    @endif

    @if($sizes->count())
    <div class="p-hover-bar">
      <div class="p-hover-lbl">Select Size</div>
      <div class="p-sz-row">
        @foreach($sizes as $sz)
        {{-- ✅ NAYA — active filter wali size pehle se highlighted --}}
        <button class="p-sz {{ $activeSizeId == $sz->id ? 'on' : '' }}"
                data-size="{{ $sz->id }}"
                onclick="event.preventDefault()">
          {{ $sz->value }}
        </button>
        @endforeach
      </div>
      @if($colors->count())
      <div class="p-cl-row">
        @foreach($colors as $cl)
        <span class="p-cl {{ $activeColorId == $cl->id ? 'on' : '' }}"
              style="background:{{ $cl->meta ?? $cl->value }}"
              title="{{ $cl->label }}"
              data-color="{{ $cl->id }}"></span>
        @endforeach
      </div>
      @endif
    </div>
    @endif

  </a>

  <div class="p-info">
    <a href="{{ route('product.show', $product->slug) }}" class="p-name">
      {{ $product->name }}
    </a>
    <div class="p-pricing">
      {{-- ✅ NAYA — display price ab variant-aware hai --}}
      <span class="p-now">
        ₹{{ number_format($displaySale) }}
      </span>
      @if($product->sale_price)
        <span class="p-was">₹{{ number_format($displayMrp) }}</span>
        <span class="p-pct">{{ $displayPct }}% off</span>
      @endif
    </div>
  </div>

</div>