@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Add Mood Card</h4>
      <p class="text-muted mb-0">Portrait image + 2 line text + category link</p>
    </div>
    <a href="{{ route('admin.mood-sections.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  <form action="{{ route('admin.mood-sections.store') }}"
        method="POST" enctype="multipart/form-data">
  @csrf

  <div class="row g-4">

    {{-- LEFT: Image + Preview --}}
    <div class="col-md-5">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Image</h5>
        </div>
        <div class="card-body pt-4">
          <div id="imgBox"
               onclick="document.getElementById('moodImg').click()"
               style="border:2px dashed #d9dee3;border-radius:8px;
                      cursor:pointer;overflow:hidden;background:#f8f9fa;
                      aspect-ratio:3/4;display:flex;
                      align-items:center;justify-content:center;
                      transition:border-color .2s;position:relative"
               onmouseover="this.style.borderColor='#696cff'"
               onmouseout="this.style.borderColor='#d9dee3'">

            <div id="imgPh" class="text-center text-muted py-4">
              <i class="bx bx-image-add" style="font-size:3rem;color:#b0b8c8"></i>
              <p class="mt-2 mb-1 fw-semibold">Portrait Image Upload Karo</p>
              <small>Recommended: 400×533px (3:4 ratio)</small><br>
              <small>JPG / PNG / WEBP — Max 5MB</small>
            </div>

            <img id="imgPrev" src="" alt=""
                 style="display:none;position:absolute;
                        inset:0;width:100%;height:100%;object-fit:cover">

            {{-- Live label preview --}}
            <div id="labelPreview"
                 style="position:absolute;bottom:0;left:0;right:0;
                        background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 70%);
                        padding:28px 14px 14px;display:none">
              <div id="prevTop"
                   style="font-size:11px;font-weight:600;color:rgba(255,255,255,.85);
                          text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">
              </div>
              <div id="prevMain"
                   style="font-size:18px;font-weight:900;color:white;
                          text-transform:uppercase;letter-spacing:.5px">
              </div>
            </div>

          </div>

          <input type="file" id="moodImg" name="image"
                 accept="image/*" class="d-none"
                 onchange="previewMood(this)" required>

          @error('image')
            <div class="text-danger mt-2" style="font-size:.82rem">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    {{-- RIGHT: Settings --}}
    <div class="col-md-7">

      {{-- Text Labels --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Labels</h5>
        </div>
        <div class="card-body pt-4">

          <div class="mb-4">
            <label class="form-label fw-semibold">
              Admin Label
              <span class="badge bg-label-secondary ms-1" style="font-size:.7rem">
                sirf admin ke liye
              </span>
            </label>
            <input type="text" name="admin_label" class="form-control"
                   placeholder="e.g. Men's Basics Card"
                   value="{{ old('admin_label') }}">
            <div class="form-text">Frontend pe nahi dikhega</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">
              Top Label
              <span class="text-muted fw-normal">(chhota text upar)</span>
            </label>
            <input type="text" name="label_top" id="labelTopInput"
                   class="form-control"
                   placeholder="e.g. MEN EVERYDAY"
                   value="{{ old('label_top') }}"
                   oninput="updatePreview()">
          </div>

          <div class="mb-0">
            <label class="form-label fw-semibold">
              Main Label
              <span class="text-muted fw-normal">(bada text neeche)</span>
            </label>
            <input type="text" name="label_main" id="labelMainInput"
                   class="form-control"
                   placeholder="e.g. BASICS"
                   value="{{ old('label_main') }}"
                   oninput="updatePreview()">
          </div>

        </div>
      </div>

      {{-- Link --}}
      <div class="card mb-4">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Card Link</h5>
        </div>
        <div class="card-body pt-4">

          <div class="mb-4">
            <label class="form-label fw-semibold">
              Category Select karo
              <span class="badge bg-label-success ms-1" style="font-size:.7rem">
                Recommended
              </span>
            </label>
            <select name="category_id" class="form-select"
                    onchange="updateCategoryUrl(this)">
              <option value="">— Koi category select nahi —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}"
                      data-slug="{{ $cat->slug }}"
                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->parent_id ? '↳ ' : '' }}{{ $cat->name }}
              </option>
              @endforeach
            </select>
            <div class="form-text">
              Yeh select karne pe link auto-generate hoga:
              <strong id="generatedUrl">/collections/...</strong>
            </div>
          </div>

          <div class="divider my-3">
            <div class="divider-text text-muted" style="font-size:.78rem">
              YA custom URL daalo
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fw-semibold">Custom URL</label>
            <input type="text" name="custom_url" class="form-control"
                   placeholder="/collections/mens-basics"
                   value="{{ old('custom_url') }}">
            <div class="form-text">
              Category select kiya ho toh yeh ignore hoga
            </div>
          </div>

        </div>
      </div>

      {{-- Sort + Active --}}
      <div class="card">
        <div class="card-body pt-4">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Sort Order</label>
              <input type="number" name="sort_order" class="form-control"
                     value="{{ old('sort_order', 0) }}" min="0">
              <div class="form-text">1 = pehle dikhega</div>
            </div>
            <div class="col-md-8 d-flex align-items-end pb-1">
              <label class="switch switch-primary">
                <input type="checkbox" class="switch-input"
                       name="is_active" value="1" checked>
                <span class="switch-toggle-slider">
                  <span class="switch-on"><i class="bx bx-check"></i></span>
                  <span class="switch-off"><i class="bx bx-x"></i></span>
                </span>
                <span class="switch-label">Active (homepage pe dikhega)</span>
              </label>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="d-flex gap-2 mt-4 mb-5">
    <button type="submit" class="btn btn-primary px-4">
      <i class="bx bx-check me-1"></i> Save Mood Card
    </button>
    <a href="{{ route('admin.mood-sections.index') }}"
       class="btn btn-outline-secondary">Cancel</a>
  </div>

  </form>
</div>
@endsection

@section('scripts')
<script>
function previewMood(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('imgPh').style.display = 'none';
      const img = document.getElementById('imgPrev');
      img.src = e.target.result;
      img.style.display = 'block';
      document.getElementById('labelPreview').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function updatePreview() {
  const top  = document.getElementById('labelTopInput').value;
  const main = document.getElementById('labelMainInput').value;
  document.getElementById('prevTop').textContent  = top;
  document.getElementById('prevMain').textContent = main;

  // Show preview overlay only if image is uploaded
  const img = document.getElementById('imgPrev');
  if (img.style.display === 'block') {
    document.getElementById('labelPreview').style.display = 'block';
  }
}

function updateCategoryUrl(sel) {
  const opt  = sel.options[sel.selectedIndex];
  const slug = opt.getAttribute('data-slug');
  const urlEl = document.getElementById('generatedUrl');
  if (slug) {
    urlEl.textContent = '/collections/' + slug;
    urlEl.style.color = '#696cff';
  } else {
    urlEl.textContent = '/collections/...';
    urlEl.style.color = '';
  }
}
</script>
@endsection