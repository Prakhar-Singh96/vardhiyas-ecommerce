@extends('admin.layout.layout')

@section('title', 'Edit: ' . $category->name)
@section('page-title', 'Edit Category')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0" style="color:#566a7f">Edit: <strong>{{ $category->name }}</strong></h5>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3">Category Details</div>
                <div class="card-body">

                    <form id="editForm" action="{{ route('admin.categories.update', $category) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-500">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $category->name) }}" oninput="generateSlug(this.value)">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-500 text-muted" style="font-size:.8rem">URL Slug</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/category/</span>
                                    <input type="text" id="slugPreview" class="form-control form-control-sm text-muted"
                                        value="{{ $category->slug }}" readonly style="font-size:.8rem;background:#f8f9fa">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-500">Parent Category</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">— None (Top level) —</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-500">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $category->sort_order) }}" min="0">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-500">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            {{-- Image --}}
            <div class="card mb-3">
                <div class="card-header py-3">Category Image</div>
                <div class="card-body">
                    <div id="imagePreviewBox"
                        style="width:100%;height:160px;border:2px dashed #d9dee3;border-radius:8px;
                    cursor:pointer;overflow:hidden;background:#fafafa;
                    display:flex;align-items:center;justify-content:center"
                        onclick="document.getElementById('imageInput').click()">
                        @if ($category->image)
                            <img id="imagePreviewImg" src="{{ asset('storage/' . $category->image) }}"
                                style="width:100%;height:100%;object-fit:cover">
                        @else
                            <div id="imagePlaceholder" class="text-center text-muted">
                                <i class="bi bi-cloud-upload fs-2 d-block mb-1"></i>
                                <small>Click karke image change karo</small>
                            </div>
                            <img id="imagePreviewImg" src=""
                                style="display:none;width:100%;height:100%;object-fit:cover">
                        @endif
                    </div>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="d-none"
                        onchange="previewImage(this)" form="editForm">
                    <small class="text-muted d-block mt-2">Naya image upload karo (optional)</small>
                </div>
            </div>

            {{-- Status --}}
            <div class="card">
                <div class="card-header py-3">Status</div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }} form="editForm">
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-3" style="border-top:1px solid #eee">
                <label class="switch switch-success">
                    <input type="checkbox" class="switch-input" name="show_in_collection" value="1"
                        {{ old('show_in_collection', $category->show_in_collection) ? 'checked' : '' }} form="editForm">
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
                      {{ old('is_look_category', $category->is_look_category) ? 'checked' : '' }} form="editForm">
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

            {{-- Sub-categories info --}}
            @if ($category->children->count() > 0)
                <div class="card mt-3">
                    <div class="card-header py-3">Sub-categories ({{ $category->children->count() }})</div>
                    <div class="card-body p-2">
                        @foreach ($category->children as $child)
                            <div class="d-flex align-items-center justify-content-between px-2 py-1">
                                <span style="font-size:.875rem;color:#566a7f">
                                    <i class="bi bi-arrow-return-right me-1 text-muted"></i>{{ $child->name }}
                                </span>
                                <a href="{{ route('admin.categories.edit', $child) }}"
                                    class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:.75rem">Edit</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function generateSlug(val) {
            document.getElementById('slugPreview').value =
                val.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const placeholder = document.getElementById('imagePlaceholder');
                    if (placeholder) placeholder.style.display = 'none';
                    const img = document.getElementById('imagePreviewImg');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
