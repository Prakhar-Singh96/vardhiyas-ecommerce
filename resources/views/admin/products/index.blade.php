@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Products</h4>
      <p class="text-muted mb-0">Saare products manage karo</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> New Product
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Filters bar --}}
  <div class="card mb-4">
    <div class="card-body py-3">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control"
                 placeholder="Product name search..."
                 value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <select name="category_id" class="form-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}"
                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Inactive</option>
            <option value="draft"    {{ request('status')=='draft'    ? 'selected':'' }}>Draft</option>
          </select>
        </div>
        <div class="col-auto d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-filter-alt me-1"></i> Filter
          </button>
          <a href="{{ route('admin.products.index') }}"
             class="btn btn-outline-secondary">Reset</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Table --}}
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-basic table border-top">
        <thead>
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
          <tr>
            <td>
              @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                     class="rounded" style="width:44px;height:44px;object-fit:cover">
              @else
                <div class="avatar">
                  <span class="avatar-initial rounded bg-label-secondary">
                    <i class="bx bx-image"></i>
                  </span>
                </div>
              @endif
            </td>
            <td>
              <span class="fw-semibold d-block">{{ $product->name }}</span>
              <small class="text-muted">SKU: {{ $product->sku ?? '—' }}</small>
            </td>
            <td>
              <span class="badge bg-label-primary rounded-pill">
                {{ $product->category->name }}
              </span>
            </td>
            <td>
              <span class="fw-semibold d-block">₹{{ number_format($product->price) }}</span>
              @if($product->sale_price)
                <small class="text-success">
                  ₹{{ number_format($product->sale_price) }}
                </small>
              @endif
            </td>
            <td>
              @php $stock = $product->variants->sum('stock'); @endphp
              <span class="fw-semibold {{ $stock > 0 ? 'text-success' : 'text-danger' }}">
                {{ $stock }}
              </span>
              <small class="text-muted d-block">
                {{ $product->variants_count }} variants
              </small>
            </td>
            <td>
              @php
                $statusMap = [
                  'active'   => 'success',
                  'inactive' => 'danger',
                  'draft'    => 'warning',
                ];
              @endphp
              <span class="badge bg-label-{{ $statusMap[$product->status] ?? 'secondary' }} rounded-pill">
                {{ ucfirst($product->status) }}
              </span>
            </td>
            <td>
              @if($product->is_featured)
                <i class="bx bxs-star text-warning fs-5"></i>
              @else
                <i class="bx bx-star text-muted fs-5"></i>
              @endif
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                   title="Edit">
                  <i class="bx bx-edit-alt"></i>
                </a>
                <button type="button"
                        class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                        title="Delete"
                        onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                  <i class="bx bx-trash"></i>
                </button>
              </div>
              <form id="del-{{ $product->id }}"
                    action="{{ route('admin.products.destroy', $product) }}"
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
                <p class="text-muted mb-1">Koi product nahi.</p>
                <a href="{{ route('admin.products.create') }}"
                   class="btn btn-sm btn-primary mt-2">
                  <i class="bx bx-plus me-1"></i> Pehla product banao
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($products->hasPages())
    <div class="card-footer d-flex justify-content-end border-top">
      {{ $products->links() }}
    </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
function confirmDelete(id, name) {
  if (confirm(`"${name}" delete karna chahte ho?`)) {
    document.getElementById('del-' + id).submit();
  }
}
</script>
@endsection