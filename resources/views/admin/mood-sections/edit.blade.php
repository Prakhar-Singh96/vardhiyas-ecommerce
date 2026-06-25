@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Edit Mood Card</h4>
      <p class="text-muted mb-0">
        {{ $moodSection->admin_label ?? 'Card #'.$moodSection->id }}
      </p>
    </div>
    <a href="{{ route('admin.mood-sections.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  <form action="{{ route('admin.mood-sections.update', $moodSection) }}"
        method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')

  <div class="row g-4">

    {{-- LEFT: Image --}}
    <div class="col-md-5">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Image</h5>
        </div>
        <div class="card-body pt-4">
          <div onclick="document.getElementById('moodImg').click()"
               style="border:2px dashed #d9dee3;border-radius:8px;
                      cursor:pointer;overflow:hidden;background:#f8f9fa;
                      aspect-ratio:3/4;display:flex;
                      align-items:center;justify-content:center;
                      transition:border-color .2s;position:relative"
               onmouseover="this.style.borderColor='#696cff'"
               onmouseout="this.style.borderColor='#d9dee3'">

            <img id="imgPrev"
                 src="{{ asset('storage/'.$moodSection->image) }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">

            {{-- Label overlay --}}
            <div style="position:absolute;bottom:0;left:0;right:0;
                        background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 70%);
                        padding:28px 14px 14px">
              <div id="prevTop"
                   style="font-size:11px;font-weight:600;color:rgba(255,255,255,.85);
                          text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">
                {{ $moodSection->label_top }}
              </div>
              <div id="prevMain"
                   style="font-size:18px;font-weight:900;color:white;text-transform:uppercase">
                {{ $moodSection->label_main }}
              </div>
            </div>

            <div style="position:absolute;top:0;left:0;right:0;bottom:0;
                        background:rgba(0,0,0,.35);display:flex;
                        align-items:center;justify-content:center;
                        opacity:0;transition:.2s"
                 onmouseover="this.style.opacity='1'"
                 onmouseout="this.style.opacity='0'">
              <span style="color:white;font-size:.9rem;font-weight:600">
                <i class="bx bx-upload me-1"></i> Change Image
              </span>
            </div>

          </div>

          <input type="file" id="moodImg" name="image"
                 accept="image/*" class="d-none"
                 onchange="previewMood(this)">
          <div class="form-text mt-2">
            Click karke replace karo ya waise hi rakho
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-md-7">

      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Labels</h5>
        </div>
        <div class="card-body pt-4">

          <div class="mb-4">
            <label class="form-label fw-semibold">Admin Label</label>
            <input type="text" name="admin_label" class="form-control"
                   value="{{ old('admin_label', $moodSection->admin_label) }}">
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Top Label</label>
            <input type="text" name="label_top" id="labelTopInput"
                   class="form-control"
                   value="{{ old('label_top', $moodSection->label_top) }}"
                   oninput="updatePreview()">
          </div>

          <div class="mb-0">
            <label class="form-label fw-semibold">Main Label</label>
            <input type="text" name="label_main" id="labelMainInput"
                   class="form-control"
                   value="{{ old('label_main', $moodSection->label_main) }}"
                   oninput="updatePreview()">
          </div>

        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Link</h5>
        </div>
        <div class="card-body pt-4">

          <div class="mb-4">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select"
                    onchange="updateCategoryUrl(this)">
              <option value="">— None —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}"
                      data-slug="{{ $cat->slug }}"
                {{ old('category_id', $moodSection->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->parent_id ? '↳ ' : '' }}{{ $cat->name }}
              </option>
              @endforeach
            </select>
            <div class="form-text">
              Generated URL:
              <strong id="generatedUrl">
                @if($moodSection->category)
                  /collections/{{ $moodSection->category->slug }}
                @else
                  /collections/...
                @endif
              </strong>
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fw-semibold">Custom URL</label>
            <input type="text" name="custom_url" class="form-control"
                   value="{{ old('custom_url', $moodSection->custom_url) }}"
                   placeholder="/collections/mens-basics">
          </div>

        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body pt-4">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Sort Order</label>
              <input type="number" name="sort_order" class="form-control"
                     value="{{ old('sort_order', $moodSection->sort_order) }}" min="0">
            </div>
            <div class="col-md-8 d-flex align-items-end pb-1">
              <label class="switch switch-primary">
                <input type="checkbox" class="switch-input"
                       name="is_active" value="1"
                       {{ $moodSection->is_active ? 'checked' : '' }}>
                <span class="switch-toggle-slider">
                  <span class="switch-on"><i class="bx bx-check"></i></span>
                  <span class="switch-off"><i class="bx bx-x"></i></span>
                </span>
                <span class="switch-label">Active</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      {{-- Danger --}}
      <div class="card border-danger">
        <div class="card-header border-danger" style="background:#fff5f5">
          <h5 class="card-title text-danger mb-0">
            <i class="bx bx-error-circle me-1"></i> Danger Zone
          </h5>
        </div>
        <div class="card-body pt-3">
          <button type="button" class="btn btn-outline-danger btn-sm w-100"
                  onclick="confirmDel()">
            <i class="bx bx-trash me-1"></i> Delete Mood Card
          </button>
        </div>
      </div>

    </div>
  </div>

  <div class="d-flex gap-2 mt-4 mb-5">
    <button type="submit" class="btn btn-primary px-4">
      <i class="bx bx-check me-1"></i> Update Card
    </button>
    <a href="{{ route('admin.mood-sections.index') }}"
       class="btn btn-outline-secondary">Cancel</a>
  </div>

  </form>

  <form id="delForm"
        action="{{ route('admin.mood-sections.destroy', $moodSection) }}"
        method="POST">
    @csrf @method('DELETE')
  </form>

</div>
@endsection

@section('scripts')
<script>
function previewMood(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('imgPrev').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function updatePreview() {
  document.getElementById('prevTop').textContent  = document.getElementById('labelTopInput').value;
  document.getElementById('prevMain').textContent = document.getElementById('labelMainInput').value;
}
function updateCategoryUrl(sel) {
  const slug = sel.options[sel.selectedIndex].getAttribute('data-slug');
  const el = document.getElementById('generatedUrl');
  el.textContent = slug ? '/collections/'+slug : '/collections/...';
}
function confirmDel() {
  if (confirm('Delete karna chahte ho?')) {
    document.getElementById('delForm').submit();
  }
}
</script>
@endsection