@extends('admin.layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">New Category</h4>
                <p class="text-muted mb-0">Parent ya sub-category dono bana sakte ho</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf

            <div class="row g-4">

                {{-- Left: Details --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">Category Details</h5>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-4">

                                {{-- Name --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Category Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="nameInput"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        placeholder="e.g. Men's Apparel" oninput="generateSlug(this.value)">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Slug preview --}}
                                <div class="col-12">
                                    <label class="form-label text-muted" style="font-size:.8rem">
                                        URL Slug (auto-generate)
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text text-muted">/category/</span>
                                        <input type="text" id="slugPreview" class="form-control text-muted" readonly
                                            style="background:#f8f9fa">
                                    </div>
                                </div>

                                {{-- Parent + Sort --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Parent Category</label>
                                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                        <option value="">— None (Top level) —</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}"
                                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Sub-category banane ke liye parent choose karo</div>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control"
                                        value="{{ old('sort_order', 0) }}" min="0">
                                    <div class="form-text">Choti number pehle dikhegi</div>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="4"
                                        placeholder="Category ke baare mein kuch likhna ho toh...">{{ old('description') }}</textarea>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex gap-2 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-1"></i> Save Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>

                {{-- Right: Image + Status --}}
                <div class="col-md-4">

                    {{-- Image Upload --}}
                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">Category Image</h5>
                        </div>
                        <div class="card-body pt-4">
                            <div id="imageDropZone" onclick="document.getElementById('imageInput').click()"
                                style="border:2px dashed #d9dee3;border-radius:6px;
                      height:160px;cursor:pointer;overflow:hidden;
                      display:flex;align-items:center;justify-content:center;
                      background:#f8f9fa;transition:border-color .2s">
                                <div id="imgPlaceholder" class="text-center text-muted px-3">
                                    <i class="bx bx-cloud-upload fs-1 d-block mb-1"></i>
                                    <small>Click karke image upload karo</small>
                                </div>
                                <img id="imgPreview" src="" alt=""
                                    style="display:none;width:100%;height:100%;object-fit:cover">
                            </div>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="d-none"
                                onchange="previewImg(this)">
                            <p class="text-muted mt-2 mb-0" style="font-size:.78rem">
                                JPG, PNG, WEBP — max 2MB
                            </p>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">Status</h5>
                        </div>
                        <div class="card-body pt-4">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_active" value="1"
                                    {{ old('is_active', '1') ? 'checked' : '' }}>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"><i class="bx bx-check"></i></span>
                                    <span class="switch-off"><i class="bx bx-x"></i></span>
                                </span>
                                <span class="switch-label">Active (frontend pe dikhegi)</span>
                            </label>
                        </div>
                    </div>
                    {{-- ✅ SIRF SUB-CATEGORY KE LIYE --}}
                    <div class="mt-3 pt-3" style="border-top:1px solid #eee">
                        <label class="switch switch-success">
                            <input type="checkbox" class="switch-input" name="show_in_collection" value="1"
                                {{ old('show_in_collection') ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="bx bx-check"></i></span>
                                <span class="switch-off"><i class="bx bx-x"></i></span>
                            </span>
                            <span class="switch-label">
                                <span class="fw-semibold d-block">
                                    Show in "Shop by Collection"
                                </span>
                                <span class="text-muted" style="font-size:.75rem">
                                    Homepage pe collection section mein dikhega
                                </span>
                            </span>
                        </label>
                        <hr class="my-3">

                        {{-- Look Section Toggle --}}
                        <label class="switch switch-primary d-flex align-items-start gap-2">
                            <input type="checkbox" class="switch-input" name="is_look_category" value="1"
                                @if (old('is_look_category', $category->is_look_category ?? false)) checked @endif>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="bx bx-check"></i></span>
                                <span class="switch-off"><i class="bx bx-x"></i></span>
                            </span>
                            <span class="switch-label">
                                <span class="fw-semibold d-block" style="font-size:.875rem">
                                    "Shop the Full Look" Section
                                </span>
                                <span class="text-muted" style="font-size:.75rem">
                                    Is category ke products homepage look slider mein dikhenge
                                </span>
                            </span>
                        </label>
                    </div>

                </div>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        function generateSlug(val) {
            document.getElementById('slugPreview').value =
                val.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function previewImg(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('imgPlaceholder').style.display = 'none';
                    const img = document.getElementById('imgPreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
