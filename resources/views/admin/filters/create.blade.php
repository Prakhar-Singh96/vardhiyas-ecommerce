{{-- resources/views/admin/filters/create.blade.php --}}
@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">New Filter Type</h4>
    <a href="{{ route('admin.filter-types.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Filter Details</h5>
        </div>
        <div class="card-body pt-4">
          <form action="{{ route('admin.filter-types.store') }}" method="POST">
            @csrf
            <div class="row g-4">

              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  Filter Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Size, Color, Stock">
                <div class="form-text">System name (unique)</div>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  Display Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="display_name"
                       class="form-control @error('display_name') is-invalid @enderror"
                       value="{{ old('display_name') }}"
                       placeholder="Size, रंग, Stock">
                <div class="form-text">Frontend pe dikhne wala naam</div>
                @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  Filter Type <span class="text-danger">*</span>
                </label>
                <select name="type"
                        class="form-select @error('type') is-invalid @enderror"
                        onchange="showHint(this.value)">
                  <option value="">— Select type —</option>
                  <option value="select"  {{ old('type')=='select'  ? 'selected':'' }}>
                    Select — (S, M, L, XL)
                  </option>
                  <option value="color"   {{ old('type')=='color'   ? 'selected':'' }}>
                    Color — (Color swatches)
                  </option>
                  <option value="boolean" {{ old('type')=='boolean' ? 'selected':'' }}>
                    Boolean — (In Stock / Out of Stock)
                  </option>
                  <option value="range"   {{ old('type')=='range'   ? 'selected':'' }}>
                    Range — (Price slider)
                  </option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Sort Order</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', 0) }}" min="0">
              </div>

              {{-- Hint box --}}
              <div class="col-12" id="hintBox" style="display:none">
                <div class="alert alert-info mb-0 py-2 d-flex align-items-center gap-2">
                  <i class="bx bx-info-circle"></i>
                  <span id="hintText"></span>
                </div>
              </div>

              <div class="col-12">
                <label class="switch switch-primary">
                  <input type="checkbox" class="switch-input"
                         name="is_active" value="1" checked>
                  <span class="switch-toggle-slider">
                    <span class="switch-on"><i class="bx bx-check"></i></span>
                    <span class="switch-off"><i class="bx bx-x"></i></span>
                  </span>
                  <span class="switch-label">Active</span>
                </label>
              </div>

            </div>

            <div class="d-flex gap-2 mt-4 pt-2 border-top">
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-check me-1"></i> Create Filter Type
              </button>
              <a href="{{ route('admin.filter-types.index') }}"
                 class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
const hints = {
  select:  'Values add karo jaise: S, M, L, XL — filter edit karke',
  color:   'Values add karo jaise: Red (#FF0000), Black (#000000) — meta field mein hex code daalo',
  boolean: 'Sirf 2 values hogi: In Stock aur Out of Stock',
  range:   'Min aur Max values set karo price range ke liye',
};
function showHint(type) {
  const box  = document.getElementById('hintBox');
  const text = document.getElementById('hintText');
  if (hints[type]) {
    text.textContent = hints[type];
    box.style.display = 'block';
  } else {
    box.style.display = 'none';
  }
}
</script>
@endsection