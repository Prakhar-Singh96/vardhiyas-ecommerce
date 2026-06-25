@extends('admin.layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Banners</h4>
                <p class="text-muted mb-0">Homepage hero aur promo banners manage karo</p>
            </div>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> New Banner
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── HERO BANNERS ── --}}
        <div class="card mb-4">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-slideshow"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Hero Slider</h5>
                        <small class="text-muted">Homepage ka main banner slider</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-primary rounded-pill">
                        {{ $heroBanners->count() }} banners
                    </span>
                    <a href="{{ route('admin.banners.create') }}?position=hero" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-plus me-1"></i> Add
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($heroBanners->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:140px">Preview</th>
                                    <th>Label</th>
                                    <th>Click URL</th>
                                    <th style="width:80px">Sort</th>
                                    <th style="width:90px">Status</th>
                                    <th style="width:100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($heroBanners as $banner)
                                    <tr>
                                        <td>
                                            <img src="{{ $banner->image_url }}"
                                                style="width:130px;height:65px;object-fit:cover;
                            border-radius:6px;border:1px solid #e0e0e0">
                                        </td>
                                        <td>
                                            <span class="fw-semibold d-block">
                                                {{ $banner->title ?? '—' }}
                                            </span>
                                            <small class="text-muted">
                                                Sort: {{ $banner->sort_order }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($banner->button_url)
                                                <code style="font-size:.8rem;color:#696cff">
                                                    {{ $banner->button_url }}
                                                </code>
                                            @else
                                                <span class="text-muted">— No URL —</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary rounded-pill">
                                                {{ $banner->sort_order }}
                                            </span>
                                        </td>
                                        <td>
                                            <label class="switch switch-sm switch-primary">
                                                <input type="checkbox" class="switch-input toggle-banner"
                                                    data-url="{{ route('admin.banners.toggle', $banner) }}"
                                                    {{ $banner->is_active ? 'checked' : '' }}>
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on"></span>
                                                    <span class="switch-off"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                                    class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                    title="Edit">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Banner delete karna chahte ho?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                                                        title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-image fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-2">Koi hero banner nahi</p>
                        <a href="{{ route('admin.banners.create') }}?position=hero" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Pehla Hero Banner Add Karo
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── PROMO BANNERS ── --}}
        <div class="card">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-image-alt"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Promo Banners</h5>
                        <small class="text-muted">Mid-page 2-column banners</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-warning rounded-pill">
                        {{ $promoBanners->count() }} banners
                    </span>
                    <a href="{{ route('admin.banners.create') }}?position=promo" class="btn btn-sm btn-outline-warning">
                        <i class="bx bx-plus me-1"></i> Add
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if ($promoBanners->count() > 0)
                    <div class="row g-3">
                        @foreach ($promoBanners as $banner)
                            <div class="col-md-6">
                                <div class="position-relative rounded overflow-hidden"
                                    style="height:160px;border:1px solid #e0e0e0">

                                    <img src="{{ $banner->image_url }}" style="width:100%;height:100%;object-fit:cover">

                                    {{-- Overlay --}}
                                    <div
                                        style="position:absolute;inset:0;
                        background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 50%);
                        display:flex;flex-direction:column;
                        justify-content:space-between;padding:10px">

                                        {{-- Top: Actions --}}
                                        <div class="d-flex justify-content-between align-items-start">

                                            {{-- Status toggle --}}
                                            <label class="switch switch-sm switch-primary">
                                                <input type="checkbox" class="switch-input toggle-banner"
                                                    data-url="{{ route('admin.banners.toggle', $banner) }}"
                                                    {{ $banner->is_active ? 'checked' : '' }}>
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on"></span>
                                                    <span class="switch-off"></span>
                                                </span>
                                            </label>

                                            {{-- Edit + Delete --}}
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                                    class="btn btn-sm btn-icon bg-white rounded-pill"
                                                    style="width:28px;height:28px" title="Edit">
                                                    <i class="bx bx-edit-alt text-primary" style="font-size:.8rem"></i>
                                                </a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete karna chahte ho?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-icon bg-white rounded-pill"
                                                        style="width:28px;height:28px" title="Delete">
                                                        <i class="bx bx-trash text-danger" style="font-size:.8rem"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Bottom: Label + URL --}}
                                        <div>
                                            <span class="text-white fw-semibold d-block" style="font-size:.9rem">
                                                {{ $banner->title ?? '—' }}
                                            </span>
                                            @if ($banner->button_url)
                                                <span style="font-size:.75rem;color:rgba(255,255,255,.7)">
                                                    <i class="bx bx-link me-1"></i>{{ $banner->button_url }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-image-alt fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-2">Koi promo banner nahi</p>
                        <a href="{{ route('admin.banners.create') }}?position=promo" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Promo Banner Add Karo
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── COLLECTION BANNER ── --}}
        <div class="card mt-4">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-collection"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Collection Banner</h5>
                        <small class="text-muted">
                            Har collection page par dikhe — ek hi banner sab pe
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-info rounded-pill">
                        {{ $collectionBanners->count() }} banner
                    </span>
                    <a href="{{ route('admin.banners.create') }}?position=collection"
                        class="btn btn-sm btn-outline-info">
                        <i class="bx bx-plus me-1"></i> Add
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($collectionBanners->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:180px">Preview</th>
                                    <th>Label</th>
                                    <th>Status</th>
                                    <th style="width:100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collectionBanners as $banner)
                                    <tr>
                                        <td>
                                            <img src="{{ $banner->image_url }}"
                                                style="width:160px;height:70px;object-fit:cover;
                          border-radius:6px;border:1px solid #e0e0e0">
                                        </td>
                                        <td>
                                            <span class="fw-semibold d-block">
                                                {{ $banner->title ?? '—' }}
                                            </span>
                                            <small class="text-muted">
                                                Har collection page par show hoga
                                            </small>
                                            <div class="mt-1">
                                                <span class="badge bg-label-info rounded-pill" style="font-size:.72rem">
                                                    <i class="bx bx-globe me-1"></i> Global Banner
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switch switch-sm switch-primary">
                                                <input type="checkbox" class="switch-input toggle-banner"
                                                    data-url="{{ route('admin.banners.toggle', $banner) }}"
                                                    {{ $banner->is_active ? 'checked' : '' }}>
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on"></span>
                                                    <span class="switch-off"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                                    class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                    title="Edit">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete karna chahte ho?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                                                        title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-collection fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-1">Koi collection banner nahi</p>
                        <small class="text-muted d-block mb-3">
                            Ek banner banao — sare collection pages par dikhe ga
                        </small>
                        <a href="{{ route('admin.banners.create') }}?position=collection"
                            class="btn btn-sm btn-info text-white">
                            <i class="bx bx-plus me-1"></i> Collection Banner Add Karo
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.toggle-banner').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const el = this;
                fetch(this.dataset.url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) el.checked = !el.checked;
                    })
                    .catch(() => {
                        el.checked = !el.checked;
                    });
            });
        });
    </script>
@endsection
