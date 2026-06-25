@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">New Product</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  <form action="{{ route('admin.products.store') }}"
        method="POST" enctype="multipart/form-data" id="productForm">
  @csrf

  <div class="row g-4">

    {{-- LEFT --}}
    <div class="col-md-8">

      {{-- Basic Info --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Basic Information</h5>
        </div>
        <div class="card-body pt-4">
          <div class="row g-4">

            <div class="col-12">
              <label class="form-label fw-semibold">
                Product Name <span class="text-danger">*</span>
              </label>
              <input type="text" name="name"
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name') }}"
                     placeholder="e.g. Waffle Henley Tshirt - Black">
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">
                Category <span class="text-danger">*</span>
              </label>
              <select name="category_id"
                      class="form-select @error('category_id') is-invalid @enderror">
                <option value="">— Select Category —</option>
                @foreach($categories as $parent)
                  <optgroup label="{{ $parent->name }}">
                    @foreach($parent->children as $child)
                      <option value="{{ $child->id }}"
                        {{ old('category_id') == $child->id ? 'selected':'' }}>
                        {{ $child->name }}
                      </option>
                    @endforeach
                    <option value="{{ $parent->id }}"
                      {{ old('category_id') == $parent->id ? 'selected':'' }}>
                      {{ $parent->name }} (General)
                    </option>
                  </optgroup>
                @endforeach
              </select>
              @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Brand</label>
              <input type="text" name="brand" class="form-control"
                     value="{{ old('brand') }}" placeholder="e.g. Vardiyash">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">SKU</label>
              <input type="text" name="sku" class="form-control"
                     value="{{ old('sku') }}" placeholder="e.g. VRD-TSH-001">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="4"
                        placeholder="Product ke baare mein detail likhao...">{{ old('description') }}</textarea>
            </div>

          </div>
        </div>
      </div>

      {{-- Pricing --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Pricing</h5>
        </div>
        <div class="card-body pt-4">
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                MRP (Original Price) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}" placeholder="1999" min="0" step="0.01">
              </div>
              @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Sale Price</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="sale_price" class="form-control"
                       value="{{ old('sale_price') }}" placeholder="1099" min="0" step="0.01">
              </div>
              <div class="form-text">MRP se kam honi chahiye</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Images --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Product Images</h5>
        </div>
        <div class="card-body pt-4">
          <div id="imageDropZone"
               onclick="document.getElementById('imageInput').click()"
               ondragover="event.preventDefault();this.style.borderColor='#696cff'"
               ondragleave="this.style.borderColor='#d9dee3'"
               ondrop="handleDrop(event)"
               style="border:2px dashed #d9dee3;border-radius:6px;padding:2rem;
                      text-align:center;cursor:pointer;background:#f8f9fa;transition:.2s">
            <i class="bx bx-cloud-upload fs-1 text-muted d-block mb-2"></i>
            <p class="text-muted mb-1">Images yahan drop karo ya click karke select karo</p>
            <small class="text-muted">JPG, PNG, WEBP — max 3MB — Pehli image primary hogi</small>
          </div>
          <input type="file" id="imageInput" name="images[]"
                 multiple accept="image/*" class="d-none"
                 onchange="previewImages(this.files)">
          <div id="imagePreviewGrid" class="row g-2 mt-2"></div>
        </div>
      </div>

      {{-- Variants --}}
      <div class="card">
        <div class="card-header border-bottom d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Variants (Size + Color + Stock)</h5>
          <button type="button" class="btn btn-sm btn-outline-primary"
                  onclick="addVariantRow()">
            <i class="bx bx-plus me-1"></i> Add Variant
          </button>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Size</th>
                <th>Color</th>
                <th>Stock</th>
                <th>Extra Price (₹)</th>
                <th style="width:50px"></th>
              </tr>
            </thead>
            <tbody id="variantRows"></tbody>
          </table>
        </div>
        <div id="noVariants" class="text-center py-3 text-muted border-top">
          <small>
            <i class="bx bx-info-circle me-1"></i>
            "Add Variant" click karo — Size M + Color Black = 1 variant
          </small>
        </div>
      </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-md-4">

      {{-- Status --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Status & Visibility</h5>
        </div>
        <div class="card-body pt-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
              <option value="active"   {{ old('status')=='active'   ? 'selected':'' }}>Active</option>
              <option value="draft"    {{ old('status')=='draft'    ? 'selected':'' }}>Draft</option>
              <option value="inactive" {{ old('status')=='inactive' ? 'selected':'' }}>Inactive</option>
            </select>
          </div>
          <label class="switch switch-warning">
            <input type="checkbox" class="switch-input"
                   name="is_featured" value="1"
                   {{ old('is_featured') ? 'checked':'' }}>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">
              <i class="bx bxs-star text-warning me-1"></i> Featured Product
            </span>
          </label>
          <label class="switch switch-warning">
            <input type="checkbox" class="switch-input"
                   name="is_bestseller" value="1"
                   {{ old('is_bestseller') ? 'checked':'' }}>
            <span class="switch-toggle-slider">
              <span class="switch-on"><i class="bx bx-check"></i></span>
              <span class="switch-off"><i class="bx bx-x"></i></span>
            </span>
            <span class="switch-label">
              <i class="bx bxs-star text-warning me-1"></i> Bestseller
            </span>
          </label>
        </div>
      </div>

      {{-- Guide --}}
      <div class="card border-primary" style="border-left:3px solid #696cff !important">
        <div class="card-body py-3">
          <p class="fw-semibold text-primary mb-2" style="font-size:.85rem">
            <i class="bx bx-bulb me-1"></i> Variant Guide
          </p>
          <ul class="mb-0 ps-3" style="font-size:.8rem;color:#697a8d">
            <li>Ek product ke multiple variants ho sakte hain</li>
            <li>Har Size + Color combo = 1 variant</li>
            <li>Har variant ka apna stock hoga</li>
            <li>Extra price = XL ke liye ₹50 extra (optional)</li>
          </ul>
        </div>
      </div>

    </div>
  </div>

  <div class="d-flex gap-2 mt-4 mb-4">
    <button type="submit" class="btn btn-primary px-4">
      <i class="bx bx-check me-1"></i> Save Product
    </button>
    <a href="{{ route('admin.products.index') }}"
       class="btn btn-outline-secondary">Cancel</a>
  </div>

  </form>
</div>
@endsection

@section('scripts')
<script>
const sizes  = @json($sizeFilter?->values ?? []);
const colors = @json($colorFilter?->values ?? []);
let variantCount = 0;

function addVariantRow() {
  document.getElementById('noVariants').style.display = 'none';
  const i = variantCount++;

  const sizeOpts  = sizes.map(s =>
    `<option value="${s.id}">${s.label}</option>`).join('');
  const colorOpts = colors.map(c =>
    `<option value="${c.id}">${c.label}</option>`).join('');

  document.getElementById('variantRows').insertAdjacentHTML('beforeend', `
  <tr id="vrow-${i}">
    <td>
      <select name="variants[${i}][size_id]" class="form-select form-select-sm">
        <option value="">— None —</option>${sizeOpts}
      </select>
    </td>
    <td>
      <select name="variants[${i}][color_id]" class="form-select form-select-sm">
        <option value="">— None —</option>${colorOpts}
      </select>
    </td>
    <td>
      <input type="number" name="variants[${i}][stock]"
             class="form-control form-control-sm" placeholder="0" min="0" required>
    </td>
    <td>
      <div class="input-group input-group-sm">
        <span class="input-group-text">₹</span>
        <input type="number" name="variants[${i}][extra_price]"
               class="form-control" placeholder="0" min="0" step="0.01">
      </div>
    </td>
    <td>
      <button type="button" class="btn btn-sm btn-icon btn-text-danger rounded-pill"
              onclick="document.getElementById('vrow-${i}').remove(); checkVariants()">
        <i class="bx bx-trash"></i>
      </button>
    </td>
  </tr>`);
}

function checkVariants() {
  const rows = document.getElementById('variantRows').children;
  document.getElementById('noVariants').style.display =
    rows.length === 0 ? 'block' : 'none';
}

function previewImages(files) {
  const grid = document.getElementById('imagePreviewGrid');
  grid.innerHTML = '';
  Array.from(files).forEach((file, i) => {
    const reader = new FileReader();
    reader.onload = e => {
      grid.insertAdjacentHTML('beforeend', `
        <div class="col-3">
          <div class="position-relative">
            <img src="${e.target.result}"
                 class="rounded w-100"
                 style="height:75px;object-fit:cover">
            ${i===0 ? '<span class="badge bg-primary position-absolute top-0 start-0 rounded-0" style="font-size:.6rem">Primary</span>' : ''}
          </div>
        </div>`);
    };
    reader.readAsDataURL(file);
  });
}

function handleDrop(e) {
  e.preventDefault();
  document.getElementById('imageDropZone').style.borderColor = '#d9dee3';
  document.getElementById('imageInput').files = e.dataTransfer.files;
  previewImages(e.dataTransfer.files);
}
</script>
@endsection