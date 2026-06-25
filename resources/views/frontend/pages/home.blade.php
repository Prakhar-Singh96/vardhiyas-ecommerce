@extends('frontend.layout.app')
@section('title', 'Vardiyash — Premium Sportswear & Combat Gear')
@section('meta', 'Shop T-Shirts, Joggers, Hoodies & Combat Gear. Free Shipping ₹999+. Easy Returns. COD Available.')

@section('content')

    {{-- ══════════════════
     HERO SLIDER
══════════════════ --}}
    <section style="padding:0">
        <div class="hero-sec">

            @foreach ($heroBanners as $banner)
                <div class="hero-slide {{ $loop->first ? 'on' : '' }}">
                    <a href="{{ $banner->button_url ?? '#' }}">
                        <picture>
                            <source media="(min-width: 768px)" srcset="{{ $banner->image_url }}">
                            <img src="{{ $banner->mobile_image_url ?? $banner->image_url }}"
                                alt="{{ $banner->title ?? 'Vardiyash' }}" class="hero-img"
                                @if (!$loop->first) loading="lazy" @endif>
                        </picture>
                    </a>
                </div>
            @endforeach

            @if ($heroBanners->count() > 1)
                <button class="hero-ctrl hero-prev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="hero-ctrl hero-next">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <div class="hero-dots">
                    @foreach ($heroBanners as $b)
                        <button class="hero-dot {{ $loop->first ? 'on' : '' }}"></button>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    @if ($moodSections->count())
        <section style="padding:44px 0 48px;background:white">
            <div style="max-width:1400px;margin:0 auto;padding:0 20px">

                {{-- Heading --}}
                <div style="text-align:center;margin-bottom:28px">
                    <h2
                        style="font-size:24px;font-weight:700;font-family:'Inter',sans-serif;
                 color:#111;margin:0 0 6px;letter-spacing:-.3px">
                        Match The Mood
                    </h2>
                    <p
                        style="font-size:13px;color:#999;font-family:'Inter',sans-serif;
                margin:0;letter-spacing:.05em">
                        Everyday Bestsellers
                    </p>
                </div>

                {{-- Grid --}}
                <div class="mood-grid">
                    @foreach ($moodSections->take(4) as $card)
                        <a href="{{ $card->url }}" class="mood-card-link">

                            <img src="{{ $card->image_url }}" alt="{{ $card->label_main ?? '' }}"
                                @if (!$loop->first) loading="lazy" @endif>

                            <div class="mood-overlay">
                                @if ($card->label_top)
                                    <div class="mood-label-top">{{ $card->label_top }}</div>
                                @endif
                                @if ($card->label_main)
                                    <div class="mood-label-main">{{ $card->label_main }}</div>
                                @endif
                            </div>

                        </a>
                    @endforeach
                </div>

            </div>
        </section>

    @endif

    {{-- ══════════════════
     SHOP BY COLLECTION
     (Subcategories grid)
══════════════════ --}}
    {{-- home.blade.php mein --}}
    @if ($subCategories->count())

        <section class="coll-section">
            <div style="max-width:1400px;margin:0 auto;padding:0 20px">

                {{-- Heading --}}
                <div style="text-align:center;margin-bottom:28px">
                    <h2
                        style="font-size:24px;font-weight:700;
                 font-family:'Inter',sans-serif;
                 color:#111;margin:0;letter-spacing:-.3px">
                        Shop by Collection
                    </h2>
                </div>

                {{-- Grid --}}
                <div class="coll-grid">
                    @foreach ($subCategories as $sub)
                        <a href="{{ route('collection.show', $sub->slug) }}" class="coll-card">

                            <div class="coll-img-wrap">
                                @if ($sub->image)
                                    <img src="{{ asset('storage/' . $sub->image) }}" alt="{{ $sub->name }}"
                                        loading="lazy">
                                @else
                                    <div class="coll-placeholder">
                                        <i class="bi bi-image" style="font-size:2rem;color:#ccc"></i>
                                    </div>
                                @endif

                                {{-- + icon --}}
                                <div class="coll-plus">+</div>
                            </div>

                            <div class="coll-name">{{ $sub->name }}</div>

                        </a>
                    @endforeach
                </div>

            </div>
        </section>

    @endif

    @if ($lookProducts->count())

        <section class="looks-section">
            <div class="looks-header">
                <h2>Shop the Full Look</h2>
            </div>

            <div class="looks-outer">

                {{-- Prev arrow --}}
                <button class="looks-arrow looks-arrow-prev disabled" id="looksPrev" onclick="looksNav(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="looks-slider-wrap">
                    <div class="looks-track" id="looksTrack">

                        @foreach ($lookProducts as $product)
                            <a href="{{ route('product.show', $product->slug) }}" class="look-card">

                                <div class="look-img-box">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                            alt="{{ $product->name }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                    @else
                                        <div
                                            style="width:100%;height:100%;background:#eee;
                          display:flex;align-items:center;justify-content:center">
                                            <i class="bi bi-image" style="font-size:2rem;color:#ccc"></i>
                                        </div>
                                    @endif

                                    {{-- Rating --}}
                                    <div class="look-rating">
                                        <i class="bi bi-star-fill"></i>
                                        5.0 | 1
                                    </div>
                                </div>

                                <div class="look-info">
                                    <div class="look-name">{{ $product->name }}</div>
                                    <div class="look-prices">
                                        <span class="look-price-now">
                                            ₹{{ number_format($product->sale_price ?? $product->price) }}
                                        </span>
                                        @if ($product->sale_price)
                                            <span class="look-price-was">
                                                ₹{{ number_format($product->price) }}
                                            </span>
                                            <span class="look-price-off">
                                                ₹{{ number_format($product->price - $product->sale_price) }} OFF
                                            </span>
                                        @endif
                                    </div>
                                    @if ($product->sale_price)
                                        <div class="look-lowest">Lowest price in last 30 days</div>
                                    @endif
                                </div>

                            </a>
                        @endforeach

                    </div>
                </div>

                {{-- Next arrow --}}
                <button class="looks-arrow looks-arrow-next {{ $lookProducts->count() <= 4 ? 'disabled' : '' }}"
                    id="looksNext" onclick="looksNav(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

            {{-- Dots --}}
            @if ($lookProducts->count() > 4)
                <div class="looks-dots" id="looksDots">
                    @php $pages = ceil($lookProducts->count() / 4); @endphp
                    @for ($i = 0; $i < $pages; $i++)
                        <button class="looks-dot {{ $i == 0 ? 'active' : '' }}"
                            onclick="looksGoTo({{ $i }})"></button>
                    @endfor
                </div>
            @endif

            {{-- View All --}}
            @if ($lookCategory)
                <a href="{{ route('collection.show', $lookCategory->slug) }}" class="looks-viewall">
                    View All
                </a>
            @endif

        </section>
    @endif

    {{-- ✅ NAYA — "Vardiyash Favourite" --}}
    @if ($favouriteProducts->count())

        <section class="vf-section">

            {{-- Heading --}}
            <div class="vf-header">
                <h2>Vardiyash Favourite</h2>
                <p>Handpicked for you</p>
            </div>

            <div class="vf-outer">

                <button class="vf-arrow vf-arrow-prev disabled" id="vfPrev" onclick="vfNav(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="vf-track-wrap">
                    <div class="vf-track" id="vfTrack">

                        @foreach ($favouriteProducts as $product)
                            <a href="{{ route('product.show', $product->slug) }}" class="vf-card">

                                <div class="vf-img-box">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                            alt="{{ $product->name }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                    @else
                                        <div
                                            style="width:100%;height:100%;background:#eee;
                          display:flex;align-items:center;justify-content:center">
                                            <i class="bi bi-image" style="font-size:2rem;color:#ccc"></i>
                                        </div>
                                    @endif

                                    <div class="vf-rating">
                                        <i class="bi bi-star-fill"></i> 5.0 | 1
                                    </div>
                                </div>

                                <div class="vf-info">
                                    <div class="vf-name">{{ $product->name }}</div>
                                    <div class="vf-prices">
                                        <span class="vf-now">
                                            ₹{{ number_format($product->sale_price ?? $product->price) }}
                                        </span>
                                        @if ($product->sale_price)
                                            <span class="vf-was">₹{{ number_format($product->price) }}</span>
                                            <span class="vf-off">
                                                ₹{{ number_format($product->price - $product->sale_price) }} OFF
                                            </span>
                                        @endif
                                    </div>
                                    @if ($product->sale_price)
                                        <div class="vf-lowest">Lowest price in last 30 days</div>
                                    @endif
                                </div>

                            </a>
                        @endforeach

                    </div>
                </div>

                <button class="vf-arrow vf-arrow-next {{ $favouriteProducts->count() <= 4 ? 'disabled' : '' }}"
                    id="vfNext" onclick="vfNav(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

            {{-- Dots --}}
            @if ($favouriteProducts->count() > 4)
                <div class="vf-dots" id="vfDots">
                    @php $vfPages = ceil($favouriteProducts->count() / 4); @endphp
                    @for ($i = 0; $i < $vfPages; $i++)
                        <button class="vf-dot {{ $i == 0 ? 'active' : '' }}"
                            onclick="vfGoTo({{ $i }})"></button>
                    @endfor
                </div>
            @endif

            {{-- Shop All Products --}}
            <a href="{{ route('favourites') }}" class="vf-viewall">Shop All Products</a>

        </section>

    @endif

    {{-- ══════════════════
     PROMO BANNERS
══════════════════ --}}
    @if ($promoBanners->count())
        <section class="bg-light">
            <div class="container">
                <div class="promo-row {{ $promoBanners->take(2)->count() == 1 ? 'single-banner' : '' }}">
                    @foreach ($promoBanners->take(2) as $promo)
                        <a href="{{ $promo->button_url ?? '#' }}" class="promo-banner">
                            <img src="{{ $promo->image_url }}" alt="{{ $promo->title ?? 'Promo' }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════
     BEST SELLERS
══════════════════ --}}
    <section>
        <div class="container">
            <div class="sec-head">
                <div>
                    <span class="sec-tag">Fan Favorites</span>
                    <h2 class="sec-title">Our Bestsellers</h2>
                    <p class="sec-sub">Loved by our community</p>
                </div>
                <a href="#" class="see-all">Shop All <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="prod-grid">
                @forelse($bestSellers as $product)
                    @include('frontend.partials.product-card', compact('product'))
                @empty
                    <div style="grid-column:1/-1;text-align:center;padding:48px 0;color:#bbb">
                        <i class="bi bi-star" style="font-size:2.5rem;display:block;margin-bottom:12px"></i>
                        <p style="font-size:14px">Admin panel se products ko Featured mark karo</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══════════════════
     NEWSLETTER
══════════════════ --}}
    <section style="background:#0f1621;padding:52px 0">
        <div class="container" style="max-width:600px;text-align:center">
            <span class="sec-tag">Stay Updated</span>
            <h2
                style="color:white;font-size:1.75rem;font-weight:800;
               letter-spacing:-.5px;margin:8px 0 10px">
                Join the Vardiyash Tribe
            </h2>
            <p style="color:#556;font-size:13.5px;margin-bottom:24px">
                Early access to new drops, exclusive deals &amp; style guides. No spam, ever.
            </p>
            <div style="display:flex;max-width:420px;margin:0 auto">
                <input type="email" placeholder="Enter your email address"
                    style="flex:1;background:#1c2535;border:1px solid #2a3545;
                    border-right:none;color:white;padding:13px 14px;
                    font-size:13px;outline:none;font-family:Inter,sans-serif">
                <button
                    style="background:var(--primary);color:white;border:none;
                     padding:13px 20px;font-size:12.5px;font-weight:700;
                     text-transform:uppercase;letter-spacing:.4px;
                     cursor:pointer;font-family:Inter,sans-serif;
                     transition:background .2s"
                    onmouseover="this.style.background='#5a1520'" onmouseout="this.style.background='var(--primary)'">
                    Subscribe
                </button>
            </div>
            <p style="color:#334;font-size:11.5px;margin-top:12px">
                Join 50,000+ athletes. Unsubscribe anytime.
            </p>
        </div>
    </section>

@endsection
