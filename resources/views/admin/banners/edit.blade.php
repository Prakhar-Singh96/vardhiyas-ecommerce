@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Edit Banner</h4>
      <p class="text-muted mb-0">
        {{ $banner->title ?? 'Banner #' . $banner->id }}
      </p>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <form action="{{ route('admin.banners.update', $banner) }}"
        method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')

  <div class="row g-4">

    {{-- LEFT: Images --}}
    <div class="col-md-8">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Banner Images</h5>
        </div>
        <div class="card-body pt-4">

          {{-- Desktop --}}
          <div class="mb-4">
            <label class="form-label fw-semibold">Desktop Image</label>
            <div onclick="document.getElementById('desktopImg').click()"
                 style="border:2px dashed #d9dee3;border-radius:8px;
                        min-height:220px;cursor:pointer;overflow:hidden;
                        display:flex;align-items:center;
                        justify-content:center;background:#f8f9fa;
                        transition:border-color .2s;position:relative"
                 onmouseover="this.style.borderColor='#696cff'"
                 onmouseout="this.style.borderColor='#d9dee3'">
              <img id="desktopPreview"
                   src="{{ $banner->image_url }}"
                   style="width:100%;height:100%;object-fit:cover;
                          position:absolute;inset:0">
              {{-- Hover overlay --}}
              <div style="position:absolute;inset:0;background:rgba(0,0,0,.4);
                          display:flex;align-items:center;justify-content:center;
                          opacity:0;transition:.2s"
                   onmouseover="this.style.opacity='1'"
                   onmouseout="this.style.opacity='0'">
                <span style="color:white;font-size:.9rem;font-weight:600">
                  <i class="bx bx-upload me-1"></i> Change Image
                </span>
              </div>
            </div>
            <input type="file" id="desktopImg" name="image"
                   accept="image/*" class="d-none"
                   onchange="previewImgReplace(this,'desktopPreview')">
            <div class="form-text">
              <i class="bx bx-info-circle me-1"></i>
              Click karke replace karo — warna current image rahegi
            </div>
          </div>

          <hr class="my-4">

          {{-- Mobile --}}
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
                        transition:border-color .2s;position:relative"
                 onmouseover="this.style.borderColor='#696cff'"
                 onmouseout="this.style.borderColor='#d9dee3'">

              @if($banner->mobile_image)
                <img id="mobilePreview"
                     src="{{ $banner->mobile_image_url }}"
                     style="width:100%;height:100%;object-fit:cover;
                            position:absolute;inset:0">
                <div style="position:absolute;inset:0;background:rgba(0,0,0,.4);
                            display:flex;align-items:center;justify-content:center;
                            opacity:0;transition:.2s"
                     onmouseover="this.style.opacity='1'"
                     onmouseout="this.style.opacity='0'">
                  <span style="color:white;font-size:.9rem;font-weight:600">
                    <i class="bx bx-upload me-1"></i> Change
                  </span>
                </div>
              @else
                <div id="mobilePlaceholder" class="text-center text-muted py-3">
                  <i class="bx bx-mobile-alt"
                     style="font-size:2.5rem;color:#b0b8c8"></i>
                  <p class="mt-2 mb-0 fw-semibold" style="font-size:.9rem">
                    Mobile image add karo (optional)
                  </p>
                </div>
                <img id="mobilePreview" src="" alt=""
                     style="display:none;width:100%;height:100%;object-fit:cover">
              @endif
            </div>
            <input type="file" id="mobileImg" name="mobile_image"
                   accept="image/*" class="d-none"
                   onchange="previewImgReplace(this,'mobilePreview','mobilePlaceholder')">
            <div class="form-text">Nahi doge toh desktop image mobile pe dikhegi</div>
          </div>

        </div>
      </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-md-4">

      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Banner Settings</h5>
        </div>
        <div class="card-body pt-4">

          <div class="mb-4">
            <label class="form-label fw-semibold">Banner Label</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $banner->title) }}"
                   placeholder="e.g. Summer Collection">
            <div class="form-text">Sirf admin ke liye</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Position</label>
            <select name="position" class="form-select">
              <option value="hero"
                {{ $banner->position == 'hero' ? 'selected' : '' }}>
                🖼️ Hero Slider
              </option>
              <option value="promo"
                {{ $banner->position == 'promo' ? 'selected' : '' }}>
                📢 Promo Banner
              </option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Click URL</label>
            <input type="text" name="button_url" class="form-control"
                   value="{{ old('button_url', $banner->button_url) }}"
                   placeholder="/products">
            <div class="form-text">Banner click pe kahan jaaye</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Sort Order</label>
            <input type="number" name="sort_order" class="form-control"
                   value="{{ old('sort_order', $banner->sort_order) }}" min="0">
          </div>

          <label class="switch switch-primary">
            <input type="checkbox" class="switch-input"
                   name="is_active" value="1"
                   {{ $banner->is_active ? 'checked' : '' }}>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">Active</span>
          </label>

        </div>
      </div>

      {{-- Banner Info --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Info</h5>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between py-2 px-3">
              <span class="text-muted" style="font-size:.875rem">Position</span>
              <span class="badge bg-label-primary rounded-pill">
                {{ ucfirst($banner->position) }}
              </span>
            </li>
            <li class="list-group-item d-flex justify-content-between py-2 px-3">
              <span class="text-muted" style="font-size:.875rem">Created</span>
              <span style="font-size:.875rem">
                {{ $banner->created_at->format('d M Y') }}
              </span>
            </li>
            <li class="list-group-item d-flex justify-content-between py-2 px-3">
              <span class="text-muted" style="font-size:.875rem">Status</span>
              <span class="badge rounded-pill
                {{ $banner->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                {{ $banner->is_active ? 'Active' : 'Inactive' }}
              </span>
            </li>
          </ul>
        </div>
      </div>

      {{-- Danger Zone --}}
      <div class="card border-danger">
        <div class="card-header border-danger" style="background:#fff5f5">
          <h5 class="card-title text-danger mb-0">
            <i class="bx bx-error-circle me-1"></i> Danger Zone
          </h5>
        </div>
        <div class="card-body pt-3">
          <p class="text-muted mb-3" style="font-size:.8rem">
            Banner permanently delete ho jaayega.
          </p>
          <button type="button" class="btn btn-outline-danger btn-sm w-100"
                  onclick="confirmDelete()">
            <i class="bx bx-trash me-1"></i> Delete Banner
          </button>
        </div>
      </div>

    </div>
  </div>

  <div class="d-flex gap-2 mt-4 mb-5">
    <button type="submit" class="btn btn-primary px-4">
      <i class="bx bx-check me-1"></i> Update Banner
    </button>
    <a href="{{ route('admin.banners.index') }}"
       class="btn btn-outline-secondary">Cancel</a>
  </div>

  </form>

  {{-- Delete form outside --}}
  <form id="deleteForm"
        action="{{ route('admin.banners.destroy', $banner) }}"
        method="POST">
    @csrf @method('DELETE')
  </form>

</div>
@endsection

@section('scripts')
<script>
function previewImgReplace(input, previewId, placeholderId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      if (placeholderId) {
        const p = document.getElementById(placeholderId);
        if (p) p.style.display = 'none';
      }
      const img = document.getElementById(previewId);
      img.src = e.target.result;
      img.style.display = 'block';
      img.style.position = 'absolute';
      img.style.inset = '0';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function confirmDelete() {
  if (confirm('Banner delete karna chahte ho?\nYeh action undo nahi hogi.')) {
    document.getElementById('deleteForm').submit();
  }
}
</script>
@endsection