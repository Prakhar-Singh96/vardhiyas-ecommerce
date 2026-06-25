@extends('admin.layout.layout')
@section('title', 'Edit: ' . $product->name)
@section('page-title', 'Edit Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="mb-0" style="color:#566a7f">Edit: <strong>{{ $product->name }}</strong></h5>
    <small class="text-muted">SKU: {{ $product->sku ?? '—' }}</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-eye me-1"></i> View
    </a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i> Back
    </a>
  </div>
</div>

<form action="{{ route('admin.products.update', $product) }}"
      method="POST" enctype="multipart/form-data" id="productForm">
@csrf
@method('PUT')

<div class="row g-3">

  {{-- ═══════════════════════════════════════
       LEFT COLUMN
  ═══════════════════════════════════════ --}}
  <div class="col-md-8">

    {{-- Basic Info --}}
    <div class="card mb-3">
      <div class="card-header py-3">Basic Information</div>
      <div class="card-body">
        <div class="row g-3">

          <div class="col-12">
            <label class="form-label fw-500">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $product->name) }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Slug preview (readonly) --}}
          <div class="col-12">
            <label class="form-label text-muted" style="font-size:.8rem">URL Slug</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text text-muted" style="font-size:.8rem">/products/</span>
              <input type="text" class="form-control form-control-sm text-muted"
                     value="{{ $product->slug }}" readonly style="background:#f8f9fa;font-size:.8rem">
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-500">Category <span class="text-danger">*</span></label>
            <select name="category_id"
                    class="form-select @error('category_id') is-invalid @enderror">
              <option value="">— Select Category —</option>
              @foreach($categories as $parent)
                <optgroup label="{{ $parent->name }}">
                  @foreach($parent->children as $child)
                    <option value="{{ $child->id }}"
                      {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                      {{ $child->name }}
                    </option>
                  @endforeach
                  <option value="{{ $parent->id }}"
                    {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }} (General)
                  </option>
                </optgroup>
              @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-500">Brand</label>
            <input type="text" name="brand" class="form-control"
                   value="{{ old('brand', $product->brand) }}" placeholder="e.g. Vardiyash">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-500">SKU</label>
            <input type="text" name="sku" class="form-control"
                   value="{{ old('sku', $product->sku) }}" placeholder="e.g. VRD-TSH-001">
          </div>

          <div class="col-12">
            <label class="form-label fw-500">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
          </div>

        </div>
      </div>
    </div>

    {{-- Pricing --}}
    <div class="card mb-3">
      <div class="card-header py-3">Pricing</div>
      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label fw-500">MRP (Original Price) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">₹</span>
              <input type="number" name="price"
                     class="form-control @error('price') is-invalid @enderror"
                     value="{{ old('price', $product->price) }}" min="0" step="0.01">
            </div>
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-500">Sale Price</label>
            <div class="input-group">
              <span class="input-group-text">₹</span>
              <input type="number" name="sale_price" class="form-control"
                     value="{{ old('sale_price', $product->sale_price) }}" min="0" step="0.01">
            </div>
            @if($product->sale_price)
              <small class="text-success fw-500">
                <i class="bi bi-tag-fill me-1"></i>
                {{ $product->discount_percent }}% discount active hai
              </small>
            @else
              <small class="text-muted">MRP se kam honi chahiye</small>
            @endif
          </div>

        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════
         EXISTING IMAGES
    ═══════════════════════════════════════ --}}
    <div class="card mb-3">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <span>Product Images</span>
        <span class="badge bg-light text-muted border">
          {{ $product->images->count() }} images
        </span>
      </div>
      <div class="card-body">

        {{-- Existing images grid --}}
        @if($product->images->count() > 0)
        <div class="row g-2 mb-3" id="existingImagesGrid">
          @foreach($product->images as $image)
          <div class="col-3" id="imgCard-{{ $image->id }}">
            <div class="position-relative" style="border-radius:8px;overflow:hidden;
                 border:2px solid {{ $image->is_primary ? '#696cff' : '#eee' }}">

              <img src="{{ asset('storage/' . $image->image_path) }}"
                   style="width:100%;height:90px;object-fit:cover">

              {{-- Primary badge --}}
              @if($image->is_primary)
                <span class="badge bg-primary position-absolute top-0 start-0"
                      style="font-size:.65rem;border-radius:0 0 6px 0">
                  Primary
                </span>
              @endif

              {{-- Action buttons --}}
              <div class="position-absolute bottom-0 start-0 end-0 d-flex"
                   style="background:rgba(0,0,0,.55)">
                @if(!$image->is_primary)
                <button type="button"
                        class="btn btn-sm text-white py-0 flex-fill"
                        style="font-size:.65rem"
                        onclick="setPrimary({{ $image->id }}, this)"
                        title="Primary banao">
                  <i class="bi bi-star"></i>
                </button>
                @endif
                <button type="button"
                        class="btn btn-sm text-danger py-0 flex-fill"
                        style="font-size:.65rem"
                        onclick="deleteImage({{ $image->id }}, this)"
                        title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </div>

            </div>
          </div>
          @endforeach
        </div>
        @endif

        {{-- Add new images --}}
        <div id="newImageDropZone"
             style="border:2px dashed #d9dee3;border-radius:8px;padding:1.5rem;
                    text-align:center;cursor:pointer;background:#fafafa;transition:.2s"
             onclick="document.getElementById('newImageInput').click()"
             ondragover="event.preventDefault();this.style.borderColor='#696cff'"
             ondragleave="this.style.borderColor='#d9dee3'"
             ondrop="handleDrop(event)">
          <i class="bi bi-cloud-upload fs-3 text-muted d-block mb-1"></i>
          <small class="text-muted">Naye images add karo (drag & drop ya click)</small>
        </div>

        <input type="file" id="newImageInput" name="new_images[]"
               multiple accept="image/*" class="d-none"
               onchange="previewNewImages(this.files)">

        <div id="newImagePreviewGrid" class="row g-2 mt-2"></div>

      </div>
    </div>

    {{-- ═══════════════════════════════════════
         VARIANTS
    ═══════════════════════════════════════ --}}
    <div class="card mb-3">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <span>Variants (Size + Color + Stock)</span>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addVariantRow()">
          <i class="bi bi-plus-lg me-1"></i> Add Variant
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0" id="variantsTable">
            <thead>
              <tr>
                <th>Size</th>
                <th>Color</th>
                <th>Stock</th>
                <th>Extra Price (₹)</th>
                <th style="width:50px"></th>
              </tr>
            </thead>
            <tbody id="variantRows">

              {{-- Existing variants --}}
              @foreach($product->variants as $variant)
              @php $vi = $loop->index; @endphp
              <tr id="vrow-{{ $vi }}">
                <td>
                  <select name="variants[{{ $vi }}][size_id]"
                          class="form-select form-select-sm">
                    <option value="">— None —</option>
                    @foreach($sizeFilter?->values ?? [] as $size)
                      <option value="{{ $size->id }}"
                        {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                        {{ $size->label }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="variants[{{ $vi }}][color_id]"
                          class="form-select form-select-sm">
                    <option value="">— None —</option>
                    @foreach($colorFilter?->values ?? [] as $color)
                      <option value="{{ $color->id }}"
                        {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                        {{ $color->label }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="number" name="variants[{{ $vi }}][stock]"
                         class="form-control form-control-sm"
                         value="{{ $variant->stock }}" min="0">
                </td>
                <td>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">₹</span>
                    <input type="number" name="variants[{{ $vi }}][extra_price]"
                           class="form-control"
                           value="{{ $variant->extra_price }}" min="0" step="0.01">
                  </div>
                </td>
                <td>
                  <button type="button" class="btn btn-sm btn-outline-danger py-0"
                          onclick="removeVariant('{{ $vi }}')">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>

        @if($product->variants->count() === 0)
        <div id="noVariants" class="text-center py-3 text-muted">
          <small>Koi variant nahi — "Add Variant" click karo</small>
        </div>
        @else
        <div id="noVariants" style="display:none"
             class="text-center py-3 text-muted">
          <small>Koi variant nahi — "Add Variant" click karo</small>
        </div>
        @endif

        {{-- Stock summary bar --}}
        @if($product->variants->count() > 0)
        <div class="px-3 py-2 border-top bg-light d-flex gap-4"
             style="font-size:.8rem;color:#566a7f">
          <span>
            <i class="bi bi-boxes me-1"></i>
            Total Variants: <strong>{{ $product->variants->count() }}</strong>
          </span>
          <span>
            <i class="bi bi-archive me-1"></i>
            Total Stock: <strong>{{ $product->variants->sum('stock') }}</strong>
          </span>
          <span>
            <i class="bi bi-check-circle me-1 text-success"></i>
            In Stock: <strong>{{ $product->variants->where('stock', '>', 0)->count() }}</strong>
          </span>
          <span>
            <i class="bi bi-x-circle me-1 text-danger"></i>
            Out of Stock: <strong>{{ $product->variants->where('stock', 0)->count() }}</strong>
          </span>
        </div>
        @endif

      </div>
    </div>

  </div>

  {{-- ═══════════════════════════════════════
       RIGHT COLUMN
  ═══════════════════════════════════════ --}}
  <div class="col-md-4">

    {{-- Status --}}
    <div class="card mb-3">
      <div class="card-header py-3">Status & Visibility</div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label fw-500">Status</label>
          <select name="status" class="form-select">
            <option value="active"   {{ old('status', $product->status) == 'active'   ? 'selected' : '' }}>
              ✅ Active
            </option>
            <option value="draft"    {{ old('status', $product->status) == 'draft'    ? 'selected' : '' }}>
              📝 Draft
            </option>
            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
              ❌ Inactive
            </option>
          </select>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox"
                 name="is_featured" id="isFeatured" value="1"
                 {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
          <label class="form-check-label" for="isFeatured">
            <i class="bi bi-star-fill text-warning me-1"></i> Featured Product
          </label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox"
                 name="is_bestseller" id="isFeatured" value="1"
                 {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
          <label class="form-check-label" for="isbestseller">
            <i class="bi bi-star-fill text-warning me-1"></i> Bestseller
          </label>
        </div>
      </div>
    </div>

    {{-- Product Info Summary --}}
    <div class="card mb-3">
      <div class="card-header py-3">Product Info</div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush" style="font-size:.85rem">
          <li class="list-group-item d-flex justify-content-between py-2">
            <span class="text-muted">Created</span>
            <span>{{ $product->created_at->format('d M Y') }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between py-2">
            <span class="text-muted">Last Updated</span>
            <span>{{ $product->updated_at->diffForHumans() }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between py-2">
            <span class="text-muted">Category</span>
            <span>{{ $product->category->name }}</span>
          </li>
          @if($product->sale_price)
          <li class="list-group-item d-flex justify-content-between py-2">
            <span class="text-muted">Discount</span>
            <span class="text-success fw-500">{{ $product->discount_percent }}% OFF</span>
          </li>
          @endif
          <li class="list-group-item d-flex justify-content-between py-2">
            <span class="text-muted">Total Stock</span>
            <span class="fw-500 {{ $product->total_stock > 0 ? 'text-success' : 'text-danger' }}">
              {{ $product->total_stock }} units
            </span>
          </li>
        </ul>
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="card border-danger">
      <div class="card-header py-3 text-danger bg-white border-danger">
        <i class="bi bi-exclamation-triangle me-1"></i> Danger Zone
      </div>
      <div class="card-body">
        <p class="text-muted mb-3" style="font-size:.8rem">
          Product delete karne pe saari images aur variants bhi delete ho jaayenge.
        </p>
        <button type="button" class="btn btn-outline-danger btn-sm w-100"
                onclick="confirmDelete()">
          <i class="bi bi-trash me-1"></i> Delete Product
        </button>
      </div>
    </div>

  </div>
</div>

{{-- Submit buttons --}}
<div class="d-flex gap-2 mb-5">
  <button type="submit" class="btn btn-primary px-4">
    <i class="bi bi-check-lg me-1"></i> Update Product
  </button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
    Cancel
  </a>
</div>

</form>

{{-- ✅ Delete form BAHAR — main form ke neeche --}}
<form id="deleteProductForm"
      action="{{ route('admin.products.destroy', $product) }}"
      method="POST">
  @csrf @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
// PHP se sizes aur colors pass karo JS mein
const sizes  = @json($sizeFilter?->values ?? []);
const colors = @json($colorFilter?->values ?? []);

// Existing variants count se start karo index
let variantCount = {{ $product->variants->count() }};

// ── Variant row add karo ──────────────────────────────
function addVariantRow() {
  document.getElementById('noVariants').style.display = 'none';
  const i = variantCount++;

  const sizeOpts = sizes.map(s =>
    `<option value="${s.id}">${s.label}</option>`).join('');
  const colorOpts = colors.map(c =>
    `<option value="${c.id}">${c.label}</option>`).join('');

  const row = `
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
      <button type="button" class="btn btn-sm btn-outline-danger py-0"
              onclick="removeVariant(${i})">
        <i class="bi bi-trash"></i>
      </button>
    </td>
  </tr>`;

  document.getElementById('variantRows').insertAdjacentHTML('beforeend', row);
}

function removeVariant(i) {
  const row = document.getElementById('vrow-' + i);
  if (row) row.remove();
  const remaining = document.getElementById('variantRows').children;
  if (remaining.length === 0) {
    document.getElementById('noVariants').style.display = 'block';
  }
}

// ── Image: new preview ────────────────────────────────
function previewNewImages(files) {
  const grid = document.getElementById('newImagePreviewGrid');
  grid.innerHTML = '';
  Array.from(files).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      grid.insertAdjacentHTML('beforeend', `
        <div class="col-3">
          <img src="${e.target.result}"
               style="width:100%;height:80px;object-fit:cover;
                      border-radius:6px;border:1px solid #eee">
          <small class="text-muted d-block text-center" style="font-size:.65rem">New</small>
        </div>`);
    };
    reader.readAsDataURL(file);
  });
}

function handleDrop(e) {
  e.preventDefault();
  document.getElementById('newImageDropZone').style.borderColor = '#d9dee3';
  const input = document.getElementById('newImageInput');
  input.files = e.dataTransfer.files;
  previewNewImages(e.dataTransfer.files);
}

// ── Image: delete via AJAX ────────────────────────────
function deleteImage(imageId, btn) {
  if (!confirm('Is image ko delete karna chahte ho?')) return;

  btn.disabled = true;

  fetch(`/admin/product-images/${imageId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
    }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      const card = document.getElementById('imgCard-' + imageId);
      card.style.opacity = '0';
      card.style.transition = 'opacity .3s';
      setTimeout(() => card.remove(), 300);
    }
  })
  .catch(() => { btn.disabled = false; });
}

// ── Image: set primary via AJAX ───────────────────────
function setPrimary(imageId, btn) {
  btn.disabled = true;

  fetch(`/admin/product-images/${imageId}/primary`, {
    method: 'PATCH',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
    }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      // Saare cards ki border reset karo
      document.querySelectorAll('#existingImagesGrid .position-relative').forEach(el => {
        el.style.border = '2px solid #eee';
      });
      // Selected card ko highlight karo
      const card = document.getElementById('imgCard-' + imageId)
                            .querySelector('.position-relative');
      card.style.border = '2px solid #696cff';

      // Page reload for badge update
      setTimeout(() => location.reload(), 500);
    }
  })
  .catch(() => { btn.disabled = false; });
}

// ── Product delete confirm ────────────────────────────
function confirmDelete() {
  if (confirm('❗ "{{ $product->name }}" permanently delete karna chahte ho?\n\nSaari images aur variants bhi delete ho jaayengi!')) {
    document.getElementById('deleteProductForm').submit();
  }
}
</script>
@endsection