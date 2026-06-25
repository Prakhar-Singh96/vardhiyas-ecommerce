@extends('admin.layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">New Banner</h4>
                <p class="text-muted mb-0">Image hi banner hai — Canva/Photoshop se design karo</p>
            </div>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                {{-- LEFT: Images --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">Banner Images</h5>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Desktop Image --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Desktop Image <span class="text-danger">*</span>
                                </label>
                                <div onclick="document.getElementById('desktopImg').click()" id="desktopBox"
                                    style="border:2px dashed #d9dee3;border-radius:8px;
                        min-height:220px;cursor:pointer;overflow:hidden;
                        display:flex;align-items:center;
                        justify-content:center;background:#f8f9fa;
                        transition:border-color .2s"
                                    onmouseover="this.style.borderColor='#696cff'"
                                    onmouseout="this.style.borderColor='#d9dee3'">
                                    <div id="desktopPlaceholder" class="text-center text-muted py-4">
                                        <i class="bx bx-image-add" style="font-size:3.5rem;color:#b0b8c8"></i>
                                        <p class="mt-2 mb-1 fw-semibold">Click karke image upload karo</p>
                                        <small class="text-muted">Recommended: 1440×600px</small><br>
                                        <small class="text-muted">JPG / PNG / WEBP — Max 5MB</small>
                                    </div>
                                    <img id="desktopPreview" src="" alt=""
                                        style="display:none;width:100%;height:100%;object-fit:cover">
                                </div>
                                <input type="file" id="desktopImg" name="image" accept="image/*" class="d-none"
                                    onchange="previewImg(this,'desktopPreview','desktopPlaceholder')">
                                @error('image')
                                    <div class="text-danger mt-1" style="font-size:.82rem">
                                        <i class="bx bx-error-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            {{-- Mobile Image --}}
                            <div>
                                <label class="form-label fw-semibold">
                                    Mobile Image
                                    <span class="badge bg-label-secondary ms-1" style="font-size:.7rem">
                                        Optional
                                    </span>
                                </label>
                                <div onclick="document.getElementById('mobileImg').click()"
                                    style="border:2px dashed #d9dee3;border-radius:8px;
                        min-height:150px;cursor:pointer;overflow:hidden;
                        display:flex;align-items:center;
                        justify-content:center;background:#f8f9fa;
                        transition:border-color .2s"
                                    onmouseover="this.style.borderColor='#696cff'"
                                    onmouseout="this.style.borderColor='#d9dee3'">
                                    <div id="mobilePlaceholder" class="text-center text-muted py-3">
                                        <i class="bx bx-mobile-alt" style="font-size:2.5rem;color:#b0b8c8"></i>
                                        <p class="mt-2 mb-1 fw-semibold" style="font-size:.9rem">
                                            Mobile banner (optional)
                                        </p>
                                        <small>Recommended: 768×900px</small>
                                    </div>
                                    <img id="mobilePreview" src="" alt=""
                                        style="display:none;width:100%;height:100%;object-fit:cover">
                                </div>
                                <input type="file" id="mobileImg" name="mobile_image" accept="image/*" class="d-none"
                                    onchange="previewImg(this,'mobilePreview','mobilePlaceholder')">
                                <div class="form-text">
                                    Nahi doge toh desktop image hi mobile pe dikhegi
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RIGHT: Settings --}}
                <div class="col-md-4">

                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">Banner Settings</h5>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Admin Label --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Banner Label</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                    placeholder="e.g. Summer Collection">
                                <div class="form-text">
                                    Sirf admin panel mein dikhega (identify karne ke liye)
                                </div>
                            </div>

                            {{-- Position --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Position <span class="text-danger">*</span>
                                </label>
                                <select name="position" class="form-select">
                                    <option value="hero"
                                        {{ old('position', $banner->position ?? '') == 'hero' ? 'selected' : '' }}>
                                        🖼️ Hero Slider — Homepage main
                                    </option>
                                    <option value="promo"
                                        {{ old('position', $banner->position ?? '') == 'promo' ? 'selected' : '' }}>
                                        📢 Promo — Mid-page (2 column)
                                    </option>

                                    {{-- ✅ NAYA --}}
                                    <option value="collection"
                                        {{ old('position', $banner->position ?? '') == 'collection' ? 'selected' : '' }}>
                                        🗂️ Collection Banner — Har collection page pe
                                    </option>
                                </select>
                            </div>

                            {{-- Click URL --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Click URL</label>
                                <input type="text" name="button_url" class="form-control"
                                    value="{{ old('button_url') }}" placeholder="/products">
                                <div class="form-text">Banner click pe kahan jaaye</div>
                            </div>

                            {{-- Sort Order --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', 0) }}" min="0">
                                <div class="form-text">0 = pehle dikhega</div>
                            </div>

                            {{-- Active --}}
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_active" value="1" checked>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"><i class="bx bx-check"></i></span>
                                    <span class="switch-off"><i class="bx bx-x"></i></span>
                                </span>
                                <span class="switch-label">Active (homepage pe dikhega)</span>
                            </label>

                        </div>
                    </div>

                    {{-- Tips --}}
                    <div class="card" style="border-left:3px solid #696cff">
                        <div class="card-body py-3">
                            <p class="fw-semibold text-primary mb-2" style="font-size:.85rem">
                                <i class="bx bx-bulb me-1"></i> Design Tips
                            </p>
                            <ul class="mb-0 ps-3" style="font-size:.78rem;color:#697a8d;line-height:1.9">
                                <li>Text image mein hi likhao (Canva/Photoshop)</li>
                                <li>Hero Desktop: <strong>1440×600px</strong></li>
                                <li>Hero Mobile: <strong>768×900px</strong></li>
                                <li>Promo: <strong>700×400px</strong></li>
                                <li>Format: JPG / PNG / WEBP</li>
                                <li>Max: 5MB per image</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex gap-2 mt-4 mb-5">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bx bx-check me-1"></i> Save Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>

        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function previewImg(input, previewId, placeholderId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const placeholder = document.getElementById(placeholderId);
                    if (placeholder) placeholder.style.display = 'none';
                    const img = document.getElementById(previewId);
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
