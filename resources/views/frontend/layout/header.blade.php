@php
    $navCats = \App\Models\Category::whereNull('parent_id')
        ->where('is_active', true)
        ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->orderBy('sort_order')
        ->get();
@endphp

{{-- Announcement --}}
<div class="ann-bar">
    <button class="ann-prev"><i class="bi bi-chevron-left"></i></button>
    <span>100% Refund Guarantee if you don't ❤️ the product. Shop with Confidence.</span>
    <button class="ann-next"><i class="bi bi-chevron-right"></i></button>
</div>

{{-- Header --}}
<header class="site-header">
    <div class="header-inner">

        {{-- Mobile toggle --}}
        <button class="mob-btn" id="mobToggle">
            <i class="bi bi-list"></i>
        </button>

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon">V</div>
            VARDHIYAS
        </a>

        {{-- Nav --}}
        <ul class="header-nav">

            @foreach ($navCats as $cat)
                <li class="nav-item">
                    {{-- ✅ FIX: button → a with href --}}
                    <a href="{{ route('collection.show', $cat->slug) }}" class="nav-btn">
                        {{ $cat->name }}
                        @if ($cat->children->count())
                            <i class="bi bi-chevron-down arr"></i>
                        @endif
                    </a>

                    @if ($cat->children->count())
                        <div class="mega-wrap">
                            <div class="mega-body">

                                <div class="mega-cats">
                                    @foreach ($cat->children as $sub)
                                        @php
                                            $bgs = [
                                                '#1a1a2e,#2d2d5a',
                                                '#2d1b33,#4a2d5a',
                                                '#1a2a1a,#2d5a2d',
                                                '#2a1a0a,#5a3010',
                                                '#0a1a2a,#1a3a5a',
                                                '#2a0a0a,#5a1010',
                                            ];
                                        @endphp
                                        {{-- ✅ FIX: real href --}}
                                        <a href="{{ route('collection.show', $sub->slug) }}" class="mega-cat">
                                            @if ($sub->image)
                                                <img src="{{ asset('storage/' . $sub->image) }}"
                                                    alt="{{ $sub->name }}" class="mega-cat-thumb" loading="lazy">
                                            @else
                                                <div class="mega-cat-placeholder"
                                                    style="background:linear-gradient(135deg,{{ $bgs[$loop->index % 6] }})">
                                                    <span
                                                        style="font-size:10px;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase">
                                                        {{ $sub->name }}
                                                    </span>
                                                </div>
                                            @endif
                                            <span class="mega-cat-lbl">{{ $sub->name }}</span>
                                        </a>
                                    @endforeach
                                </div>

                                <div class="mega-side">
                                    {{-- ✅ FIX: same category, sorted newest --}}
                                    <a href="{{ route('collection.show', $cat->slug) }}?sort=newest"
                                        class="mega-side-link">
                                        <div>
                                            <span class="mega-side-title">See the Latest</span>
                                            <span class="mega-side-sub">Explore new arrivals</span>
                                        </div>
                                        <i class="bi bi-chevron-right mega-side-arr"></i>
                                    </a>
                                    <a href="{{ route('collection.show', $cat->slug) }}?sort=bestselling"
                                        class="mega-side-link">
                                        <div>
                                            <span class="mega-side-title">Best Sellers</span>
                                            <span class="mega-side-sub">Fan favorites right now</span>
                                        </div>
                                        <i class="bi bi-chevron-right mega-side-arr"></i>
                                    </a>
                                    <a href="{{ route('favourites') }}" class="mega-side-link">
                                        <div>
                                            <span class="mega-side-title" style="color:var(--primary)">Sale</span>
                                            <span class="mega-side-sub">Up to 60% off</span>
                                        </div>
                                        <i class="bi bi-chevron-right mega-side-arr" style="color:var(--primary)"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endif
                </li>
            @endforeach

            {{-- Static quick links --}}
            @foreach ($navCats as $cat)
                @foreach ($cat->children->take(3) as $sub)
                    <li class="nav-item">
                        {{-- ✅ FIX: button → a with href --}}
                        <a href="{{ route('collection.show', $sub->slug) }}" class="nav-btn">
                            {{ $sub->name }}
                        </a>
                    </li>
                @endforeach
            @endforeach

        </ul>

        {{-- Search --}}
        <div class="header-search">
            <input type="text" placeholder='Try searching "T-shirts"'>
            <button aria-label="Search"><i class="bi bi-search"></i></button>
        </div>

        {{-- Icons --}}
        <div class="h-icons">
            <a href="#" class="h-icon" title="My Account">
                <i class="bi bi-person"></i>
            </a>
            <a href="javascript:void(0)" class="h-icon" title="Cart" onclick="openCartDrawer()">
                <i class="bi bi-bag"></i>
                <span class="h-badge" id="cartBadge" style="display:none">0</span>
            </a>
        </div>

    </div>
</header>

{{-- Mobile Menu --}}
<div class="mob-menu" id="mobMenu">
    <div class="mob-bg" id="mobBg"></div>
    <div class="mob-panel">

        <div class="mob-top">
            <a href="{{ route('home') }}" class="logo" style="font-size:1.1rem">
                <div class="logo-icon" style="width:26px;height:26px;font-size:10px">V</div>
                VARDIYASH
            </a>
            <button class="mob-close" id="mobClose"><i class="bi bi-x-lg"></i></button>
        </div>

        @foreach ($navCats as $cat)
            <div>
                @if ($cat->children->count())
                    {{-- Children hain → button toggle karta hai (dropdown khulta hai) --}}
                    <button class="mob-link" onclick="mobSub(this)">
                        {{ $cat->name }}
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="mob-sub">
                        @foreach ($cat->children as $sub)
                            {{-- ✅ FIX: real href --}}
                            <a href="{{ route('collection.show', $sub->slug) }}">{{ $sub->name }}</a>
                        @endforeach
                    </div>
                @else
                    {{-- Children nahi → seedha link --}}
                    <a href="{{ route('collection.show', $cat->slug) }}" class="mob-link">
                        {{ $cat->name }}
                    </a>
                @endif
            </div>
        @endforeach

        <a href="{{ route('favourites') }}" class="mob-link" style="color:var(--primary)">
            Crazy Deals
        </a>

        <div class="mob-extras">
            <a href="#"><i class="bi bi-search fs-5"></i> Search</a>
            <a href="#"><i class="bi bi-person fs-5"></i> My Account</a>
            <a href="javascript:void(0)" class="h-icon" title="Cart" onclick="openCartDrawer()">
                <i class="bi bi-bag"></i>
                <span class="h-badge" id="cartBadge" style="display:none">0</span>
            </a>
        </div>

    </div>
</div>
