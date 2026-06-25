{{-- resources/views/frontend/pages/collection.blade.php --}}
@extends('frontend.layout.app')

@section('title', $category->name . ' — Vardiyash')
@section('meta', 'Shop ' . $category->name . ' at Vardiyash. Free Shipping ₹999+. Easy Returns.')

@section('content')

    <style>
        /* ════════════════════════════════════
       COLLECTION PAGE — Nobero Style
    ════════════════════════════════════ */

        /* Banner */
        .coll-banner-wrap {
            position: relative;
            overflow: hidden;
            background: #111;
        }

        .coll-banner-wrap img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 320px;
            object-fit: cover;
            object-position: center;
        }

        /* Layout */
        .coll-layout {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px 60px;
            display: flex;
            gap: 32px;
            align-items: flex-start;
        }

        /* ── SIDEBAR ── */
        .coll-sidebar {
            width: 220px;
            flex-shrink: 0;
            min-width: 0;
        }

        .filter-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 15px;
            font-weight: 700;
            color: #111;
            font-family: 'Inter', sans-serif;
            padding-bottom: 14px;
            margin-bottom: 4px;
            border-bottom: 1px solid #eee;
        }

        .filter-clear {
            font-size: 12px;
            font-weight: 600;
            color: #b8860b;
            cursor: pointer;
            text-decoration: none;
        }

        .filter-clear:hover {
            text-decoration: underline;
        }

        .filter-group {
            border-bottom: 1px solid #eee;
            padding: 16px 0;
            min-width: 0;
        }

        .filter-group-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            color: #111;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            user-select: none;
        }

        .filter-group-title i {
            font-size: 12px;
            color: #999;
            transition: transform .2s;
        }

        .filter-group-title.open i {
            transform: rotate(180deg);
        }

        .filter-group-body {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 16px;
        }

        .filter-group-body.collapsed {
            display: none;
        }

        /* Generic checkbox/radio row — matches Nobero exactly */
        .f-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            min-width: 0;
        }

        .f-option input {
            width: 16px;
            height: 16px;
            accent-color: #111;
            flex-shrink: 0;
            cursor: pointer;
        }

        .f-option-label {
            font-size: 13.5px;
            color: #333;
            font-family: 'Inter', sans-serif;
            line-height: 1.3;
        }

        /* Color dot inside option */
        .f-color-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1px solid #ddd;
            flex-shrink: 0;
        }

        /* ── MAIN ── */
        .coll-main {
            flex: 1;
            min-width: 0;
        }

        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: #888;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
            flex-wrap: wrap;
        }

        .breadcrumb-row a {
            color: #888;
            transition: color .2s;
        }

        .breadcrumb-row a:hover {
            color: #111;
        }

        .breadcrumb-row span {
            color: #111;
            font-weight: 600;
        }

        .breadcrumb-sep {
            color: #ccc;
        }

        .coll-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .coll-title-row h1 {
            font-size: 24px;
            font-weight: 800;
            color: #111;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .coll-count {
            font-size: 14px;
            font-weight: 400;
            color: #999;
            margin-left: 8px;
        }

        /* Sort — Nobero pill style */
        .sort-wrap {
            position: relative;
        }

        .sort-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid #ddd;
            background: white;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            color: #333;
            cursor: pointer;
        }

        .sort-select-native {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        /* Active filter chips */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 18px;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f6f6f6;
            border: 1px solid #e5e5e5;
            padding: 5px 10px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .filter-chip button {
            color: #999;
            font-size: 14px;
            line-height: 1;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .filter-chip button:hover {
            color: #7b1f2e;
        }

        .filter-clear-all {
            font-size: 12px;
            color: #7b1f2e;
            text-decoration: underline;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }

        /* Grid */
        .prod-coll-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        /* Pagination */
        .pagination-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 44px;
        }

        .pg-btn {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #e0e0e0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            color: #333;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .pg-btn:hover {
            border-color: #111;
            color: #111;
        }

        .pg-btn.active {
            background: #111;
            border-color: #111;
            color: white;
        }

        .pg-btn.disabled {
            opacity: .35;
            cursor: not-allowed;
            pointer-events: none;
            border: none;
        }

        /* Empty */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #bbb;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #888;
            margin-bottom: 8px;
        }

        /* Mobile filter button */
        .mob-filter-btn {
            display: none;
            align-items: center;
            gap: 6px;
            border: 1.5px solid #111;
            background: white;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            color: #111;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* Mobile drawer */
        .mob-filter-drawer {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
        }

        .mob-filter-bg {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .5);
        }

        .mob-filter-panel {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 300px;
            background: white;
            overflow-y: auto;
            padding: 20px;
            transform: translateX(-100%);
            transition: transform .3s ease;
        }

        .mob-filter-drawer.open {
            display: block;
        }

        .mob-filter-drawer.open .mob-filter-panel {
            transform: translateX(0);
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .coll-sidebar {
                display: none;
            }

            .mob-filter-btn {
                display: flex;
            }

            .prod-coll-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .coll-banner-wrap img {
                max-height: 220px;
            }
        }

        @media (max-width: 575px) {
            .coll-layout {
                padding: 16px 14px 40px;
                gap: 0;
            }

            .prod-coll-grid {
                gap: 8px;
            }

            .coll-title-row h1 {
                font-size: 19px;
            }
        }
    </style>

    @php
        $collBanner = \App\Models\Banner::where('position', 'collection')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        $hasActiveFilters = request()->hasAny(['sizes', 'colors', 'price_ranges', 'availability']);
    @endphp

    {{-- ══ BANNER ══ --}}
    @if ($collBanner)
        <div class="coll-banner-wrap">
            <picture>
                <source media="(min-width:768px)" srcset="{{ $collBanner->image_url }}">
                <img src="{{ $collBanner->mobile_image_url ?? $collBanner->image_url }}"
                    alt="{{ $collBanner->title ?? 'Collection Banner' }}">
            </picture>
        </div>
    @endif

    {{-- ══ MAIN LAYOUT ══ --}}
    <form method="GET" action="{{ isset($category->id) ? route('collection.show', $category->slug) : route('favourites') }}"
        id="filterForm">

        <div class="coll-layout">

            {{-- ── SIDEBAR ── --}}
            <aside class="coll-sidebar">

                <div class="filter-head">
                    Filter
                    @if ($hasActiveFilters)
                        <a href="{{ url()->current() }}" class="filter-clear" onclick="clearAllFilters(event)">
                            Clear All
                        </a>
                    @endif
                </div>

                {{-- Price --}}
                <div class="filter-group">
                    <div class="filter-group-title open" onclick="toggleFilter(this)">
                        Price <i class="bi bi-chevron-up"></i>
                    </div>
                    <div class="filter-group-body">
                        @foreach ($priceRanges as $range)
                            <label class="f-option">
                                <input type="checkbox" name="price_ranges[]" value="{{ $range['key'] }}"
                                    {{ in_array($range['key'], (array) request('price_ranges', [])) ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span class="f-option-label">{{ $range['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Color --}}
                @if ($colorFilter && $colorFilter->values->count())
                    <div class="filter-group">
                        <div class="filter-group-title open" onclick="toggleFilter(this)">
                            Color <i class="bi bi-chevron-up"></i>
                        </div>
                        <div class="filter-group-body">
                            @foreach ($colorFilter->values as $cl)
                                <label class="f-option">
                                    <input type="checkbox" name="colors[]" value="{{ $cl->id }}"
                                        {{ in_array($cl->id, (array) request('colors', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span class="f-color-dot" style="background:{{ $cl->meta ?? $cl->value }}"></span>
                                    <span class="f-option-label">{{ $cl->label ?? $cl->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Size --}}
                @if ($sizeFilter && $sizeFilter->values->count())
                    <div class="filter-group">
                        <div class="filter-group-title open" onclick="toggleFilter(this)">
                            Size <i class="bi bi-chevron-up"></i>
                        </div>
                        <div class="filter-group-body">
                            @foreach ($sizeFilter->values as $sz)
                                <label class="f-option">
                                    <input type="checkbox" name="sizes[]" value="{{ $sz->id }}"
                                        {{ in_array($sz->id, (array) request('sizes', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span class="f-option-label">{{ $sz->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Availability --}}
                <div class="filter-group">
                    <div class="filter-group-title open" onclick="toggleFilter(this)">
                        Availability <i class="bi bi-chevron-up"></i>
                    </div>
                    <div class="filter-group-body">
                        <label class="f-option">
                            <input type="radio" name="availability" value="in_stock"
                                {{ request('availability') == 'in_stock' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="f-option-label">In stock</span>
                        </label>
                        <label class="f-option">
                            <input type="radio" name="availability" value="out_of_stock"
                                {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="f-option-label">Out of stock</span>
                        </label>
                    </div>
                </div>

            </aside>

            {{-- ── MAIN ── --}}
            <main class="coll-main">

                {{-- Breadcrumb --}}
                <div class="breadcrumb-row">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="breadcrumb-sep">›</span>
                    @if (isset($category->parent) && $category->parent)
                        <a href="{{ route('collection.show', $category->parent->slug) }}">
                            {{ $category->parent->name }}
                        </a>
                        <span class="breadcrumb-sep">›</span>
                    @endif
                    <span>{{ $category->name }}</span>
                </div>

                {{-- Title + Sort --}}
                <div class="coll-title-row">
                    <h1>
                        {{ $category->name }}
                        <span class="coll-count">{{ $products->total() }} items</span>
                    </h1>

                    <div style="display:flex;align-items:center;gap:10px">
                        <button type="button" class="mob-filter-btn" onclick="openMobFilter()">
                            <i class="bi bi-sliders"></i> Filter
                        </button>

                        <div class="sort-wrap">
                            <button type="button" class="sort-btn">
                                <i class="bi bi-arrow-down-up"></i>
                                Sort:
                                <strong>
                                    @php
                                        $sortLabels = [
                                            'featured' => 'Featured',
                                            'newest' => 'Newest',
                                            'price_asc' => 'Price: Low to High',
                                            'price_desc' => 'Price: High to Low',
                                            'name_asc' => 'Name: A-Z',
                                            'bestselling' => 'Bestsellers',
                                        ];
                                    @endphp
                                    {{ $sortLabels[request('sort', 'featured')] ?? 'Featured' }}
                                </strong>
                            </button>
                            <select name="sort" class="sort-select-native" onchange="this.form.submit()">
                                <option value="featured" {{ request('sort', 'featured') == 'featured' ? 'selected' : '' }}>
                                    Featured</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low
                                    to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z
                                </option>
                                <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>
                                    Bestsellers</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Active chips --}}
                @if ($hasActiveFilters)
                    <div class="active-filters">
                        @foreach ((array) request('price_ranges', []) as $pr)
                            @php $prLabel = collect($priceRanges)->firstWhere('key', $pr)['label'] ?? $pr; @endphp
                            <span class="filter-chip">
                                {{ $prLabel }}
                                <button type="button"
                                    onclick="removeFilterValue('price_ranges[]','{{ $pr }}')">×</button>
                            </span>
                        @endforeach

                        @foreach ((array) request('sizes', []) as $sid)
                            @php $sz = $sizeFilter?->values->firstWhere('id', $sid); @endphp
                            @if ($sz)
                                <span class="filter-chip">
                                    Size: {{ $sz->value }}
                                    <button type="button"
                                        onclick="removeFilterValue('sizes[]','{{ $sid }}')">×</button>
                                </span>
                            @endif
                        @endforeach

                        @foreach ((array) request('colors', []) as $cid)
                            @php $cl = $colorFilter?->values->firstWhere('id', $cid); @endphp
                            @if ($cl)
                                <span class="filter-chip">
                                    {{ $cl->label ?? $cl->value }}
                                    <button type="button"
                                        onclick="removeFilterValue('colors[]','{{ $cid }}')">×</button>
                                </span>
                            @endif
                        @endforeach

                        @if (request('availability'))
                            <span class="filter-chip">
                                {{ request('availability') == 'in_stock' ? 'In stock' : 'Out of stock' }}
                                <button type="button" onclick="removeFilterValue('availability', null)">×</button>
                            </span>
                        @endif

                        <a href="{{ url()->current() }}" class="filter-clear-all" onclick="clearAllFilters(event)">
                            Clear All
                        </a>
                    </div>
                @endif

                {{-- Products --}}
                @php
                    // Agar exactly 1 size/color select kiya hai toh uska price dikhao
                    $selectedSizes = (array) request('sizes', []);
                    $selectedColors = (array) request('colors', []);

                    $activeSizeId = count($selectedSizes) === 1 ? (int) $selectedSizes[0] : null;
                    $activeColorId = count($selectedColors) === 1 ? (int) $selectedColors[0] : null;
                @endphp

                {{-- Products grid mein include call update karo --}}
                @if ($products->count())
                    <div class="prod-coll-grid">
                        @foreach ($products as $product)
                            @include('frontend.partials.product-card', [
                                'product' => $product,
                                'activeSizeId' => $activeSizeId,
                                'activeColorId' => $activeColorId,
                            ])
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                        <div class="pagination-row">
                            @if ($products->onFirstPage())
                                <span class="pg-btn disabled">‹</span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="pg-btn">‹</a>
                            @endif

                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span class="pg-btn active">{{ $page }}</span>
                                @elseif(abs($page - $products->currentPage()) <= 2 || $page == 1 || $page == $products->lastPage())
                                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                                @elseif(abs($page - $products->currentPage()) == 3)
                                    <span class="pg-btn disabled">…</span>
                                @endif
                            @endforeach

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="pg-btn">›</a>
                            @else
                                <span class="pg-btn disabled">›</span>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="bi bi-bag-x"></i>
                        <h3>Koi product nahi mila</h3>
                        <p>Filters change karo ya
                            <a href="{{ url()->current() }}" style="color:#7b1f2e;text-decoration:underline"
                                onclick="clearAllFilters(event)">clear all filters</a>
                        </p>
                    </div>
                @endif

            </main>
        </div>
    </form>

    {{-- ══ MOBILE FILTER DRAWER ══ --}}
    <div class="mob-filter-drawer" id="mobFilterDrawer">
        <div class="mob-filter-bg" onclick="closeMobFilter()"></div>
        <div class="mob-filter-panel">

            <div
                style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #eee">
                <span style="font-size:15px;font-weight:700;font-family:'Inter',sans-serif">Filters</span>
                <button onclick="closeMobFilter()"
                    style="background:none;border:none;font-size:22px;cursor:pointer;color:#111">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="GET"
                action="{{ isset($category->id) ? route('collection.show', $category->slug) : route('favourites') }}">

                <div class="filter-group">
                    <div class="filter-group-title" style="margin-bottom:14px">Price</div>
                    <div class="filter-group-body" style="margin-top:0">
                        @foreach ($priceRanges as $range)
                            <label class="f-option">
                                <input type="checkbox" name="price_ranges[]" value="{{ $range['key'] }}"
                                    {{ in_array($range['key'], (array) request('price_ranges', [])) ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span class="f-option-label">{{ $range['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @if ($colorFilter && $colorFilter->values->count())
                    <div class="filter-group">
                        <div class="filter-group-title" style="margin-bottom:14px">Color</div>
                        <div class="filter-group-body" style="margin-top:0">
                            @foreach ($colorFilter->values as $cl)
                                <label class="f-option">
                                    <input type="checkbox" name="colors[]" value="{{ $cl->id }}"
                                        {{ in_array($cl->id, (array) request('colors', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span class="f-color-dot" style="background:{{ $cl->meta ?? $cl->value }}"></span>
                                    <span class="f-option-label">{{ $cl->label ?? $cl->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($sizeFilter && $sizeFilter->values->count())
                    <div class="filter-group">
                        <div class="filter-group-title" style="margin-bottom:14px">Size</div>
                        <div class="filter-group-body" style="margin-top:0">
                            @foreach ($sizeFilter->values as $sz)
                                <label class="f-option">
                                    <input type="checkbox" name="sizes[]" value="{{ $sz->id }}"
                                        {{ in_array($sz->id, (array) request('sizes', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span class="f-option-label">{{ $sz->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="filter-group">
                    <div class="filter-group-title" style="margin-bottom:14px">Availability</div>
                    <div class="filter-group-body" style="margin-top:0">
                        <label class="f-option">
                            <input type="radio" name="availability" value="in_stock"
                                {{ request('availability') == 'in_stock' ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="f-option-label">In stock</span>
                        </label>
                        <label class="f-option">
                            <input type="radio" name="availability" value="out_of_stock"
                                {{ request('availability') == 'out_of_stock' ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="f-option-label">Out of stock</span>
                        </label>
                    </div>
                </div>

                <a href="{{ url()->current() }}"
                    style="display:block;text-align:center;margin-top:10px;font-size:13px;color:#888;font-family:'Inter',sans-serif"
                    onclick="clearAllFilters(event)">
                    Clear All
                </a>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleFilter(el) {
            el.classList.toggle('open');
            const body = el.nextElementSibling;
            if (body) body.classList.toggle('collapsed');
        }

        function openMobFilter() {
            document.getElementById('mobFilterDrawer').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobFilter() {
            document.getElementById('mobFilterDrawer').classList.remove('open');
            document.body.style.overflow = '';
        }

        function removeFilterValue(name, val) {
            const url = new URL(window.location.href);
            if (val === null) {
                url.searchParams.delete(name);
            } else {
                const values = url.searchParams.getAll(name).filter(v => v !== String(val));
                url.searchParams.delete(name);
                values.forEach(v => url.searchParams.append(name, v));
            }
            window.location = url.toString();
        }

        function clearAllFilters(e) {
            e.preventDefault();
            window.location = window.location.pathname;
        }
    </script>
@endpush
