@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">All Categories</h4>
      <p class="text-muted mb-0">Parent categories aur unki sub-categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> New Category
    </a>
  </div>

  {{-- Flash Messages --}}
  @if(session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="bx bx-check-circle"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="bx bx-error-circle"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Table Card --}}
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-basic table border-top">
        <thead>
          <tr>
            <th>#</th>
            <th>Category</th>
            <th>Parent</th>
            <th>Sub-cats</th>
            <th>Products</th>
            <th>Sort</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>
              <div class="d-flex align-items-center gap-3">
                {{-- Image --}}
                @if($category->image)
                  <img src="{{ asset('storage/' . $category->image) }}"
                       alt="{{ $category->name }}"
                       class="rounded"
                       style="width:38px;height:38px;object-fit:cover">
                @else
                  <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded bg-label-primary">
                      {{ strtoupper(substr($category->name, 0, 2)) }}
                    </span>
                  </div>
                @endif
                {{-- Name + slug --}}
                <div>
                  <span class="fw-semibold d-block">{{ $category->name }}</span>
                  <small class="text-muted">/{{ $category->slug }}</small>
                </div>
              </div>

              {{-- Sub-category badges --}}
              @if($category->children->count() > 0)
              <div class="mt-2 ps-5 d-flex flex-wrap gap-1">
                @foreach($category->children as $child)
                <span class="badge bg-label-primary rounded-pill" style="font-size:.72rem">
                  <i class="bx bx-subdirectory-right me-1"></i>{{ $child->name }}
                  <a href="{{ route('admin.categories.edit', $child) }}"
                     class="text-primary ms-1">
                    <i class="bx bx-edit" style="font-size:.7rem"></i>
                  </a>
                </span>
                @endforeach
              </div>
              @endif
            </td>
            <td>
              @if($category->parent)
                <span class="badge bg-label-success rounded-pill">
                  {{ $category->parent->name }}
                </span>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <span class="badge bg-label-info rounded-pill">
                {{ $category->children_count }}
              </span>
            </td>
            <td>{{ $category->products_count }}</td>
            <td>{{ $category->sort_order }}</td>
            <td>
              {{-- Toggle switch --}}
              <label class="switch switch-primary">
                <input type="checkbox" class="switch-input toggle-status"
                       data-url="{{ route('admin.categories.toggle', $category) }}"
                       {{ $category->is_active ? 'checked' : '' }}>
                <span class="switch-toggle-slider">
                  <span class="switch-on"><i class="bx bx-check"></i></span>
                  <span class="switch-off"><i class="bx bx-x"></i></span>
                </span>
              </label>
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                  <i class="bx bx-edit-alt"></i>
                </a>
                <button type="button"
                        class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                        onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')">
                  <i class="bx bx-trash"></i>
                </button>
              </div>

              <form id="del-{{ $category->id }}"
                    action="{{ route('admin.categories.destroy', $category) }}"
                    method="POST" class="d-none">
                @csrf @method('DELETE')
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/128/7486/7486744.png"
                     width="64" class="mb-3 opacity-50">
                <p class="text-muted mb-1">Koi category nahi mili.</p>
                <a href="{{ route('admin.categories.create') }}"
                   class="btn btn-sm btn-primary mt-2">
                  <i class="bx bx-plus me-1"></i> Pehli category banao
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($categories->hasPages())
    <div class="card-footer d-flex justify-content-end">
      {{ $categories->links() }}
    </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
// Status toggle
document.querySelectorAll('.toggle-status').forEach(toggle => {
  toggle.addEventListener('change', function () {
    const url = this.dataset.url;
    const el  = this;
    fetch(url, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(r => r.json())
    .then(data => { if (!data.success) el.checked = !el.checked; })
    .catch(() => { el.checked = !el.checked; });
  });
});

function confirmDelete(id, name) {
  if (confirm(`"${name}" delete karna chahte ho?\nYeh action undo nahi hogi.`)) {
    document.getElementById('del-' + id).submit();
  }
}
</script>
@endsection