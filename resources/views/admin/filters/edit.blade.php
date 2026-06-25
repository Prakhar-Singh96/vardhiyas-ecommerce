@extends('admin.layout.layout')

@section('title', 'Edit: ' . $filterType->display_name)
@section('page-title', 'Edit Filter')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0" style="color:#566a7f">Edit: <strong>{{ $filterType->display_name }}</strong></h5>
  <a href="{{ route('admin.filter-types.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Back
  </a>
</div>

<div class="row g-3">

  {{-- Left: Filter Type edit --}}
  <div class="col-md-5">
    <div class="card">
      <div class="card-header py-3">Filter Type Details</div>
      <div class="card-body">
        <form action="{{ route('admin.filter-types.update', $filterType) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-500">Filter Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $filterType->name) }}">
          </div>

          <div class="mb-3">
            <label class="form-label fw-500">Display Name</label>
            <input type="text" name="display_name" class="form-control"
                   value="{{ old('display_name', $filterType->display_name) }}">
          </div>

          <div class="mb-3">
            <label class="form-label fw-500">Type</label>
            <select name="type" class="form-select">
              @foreach(['select','color','boolean','range'] as $t)
              <option value="{{ $t }}" {{ $filterType->type == $t ? 'selected' : '' }}>
                {{ ucfirst($t) }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-500">Sort Order</label>
            <input type="number" name="sort_order" class="form-control"
                   value="{{ old('sort_order', $filterType->sort_order) }}" min="0">
          </div>

          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_active"
                   id="isActive" value="1" {{ $filterType->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="isActive">Active</label>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-lg me-1"></i> Update
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Right: Values manage --}}
  <div class="col-md-7">
    <div class="card">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <span>Filter Values</span>
        <span class="badge bg-primary rounded-pill">{{ $filterType->values->count() }} values</span>
      </div>
      <div class="card-body">

        {{-- Add new value form --}}
        <form action="{{ route('admin.filter-values.store', $filterType) }}" method="POST">
          @csrf
          <div class="row g-2 align-items-end mb-3">

            <div class="col">
              <label class="form-label fw-500" style="font-size:.8rem">Value (system)</label>
              <input type="text" name="value" class="form-control form-control-sm"
                     placeholder="{{ $filterType->type == 'color' ? '#FF0000' : 'XL' }}"
                     required>
            </div>

            <div class="col">
              <label class="form-label fw-500" style="font-size:.8rem">Label (display)</label>
              <input type="text" name="label" class="form-control form-control-sm"
                     placeholder="{{ $filterType->type == 'color' ? 'Red' : 'Extra Large' }}"
                     required>
            </div>

            @if($filterType->type === 'color')
            <div class="col-auto">
              <label class="form-label fw-500" style="font-size:.8rem">Hex Color</label>
              <input type="color" name="meta" class="form-control form-control-color form-control-sm"
                     value="#696cff" title="Color choose karo">
            </div>
            @endif

            <div class="col-auto">
              <label class="form-label fw-500" style="font-size:.8rem">Order</label>
              <input type="number" name="sort_order" class="form-control form-control-sm"
                     value="0" min="0" style="width:60px">
            </div>

            <div class="col-auto">
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add
              </button>
            </div>
          </div>
        </form>

        <hr class="my-2">

        {{-- Existing values list --}}
        @forelse($filterType->values as $val)
        <div class="d-flex align-items-center gap-2 py-2 border-bottom" id="val-row-{{ $val->id }}">

          {{-- Color preview --}}
          @if($filterType->type === 'color')
            <span style="width:24px;height:24px;border-radius:50%;flex-shrink:0;
                         background:{{ $val->meta ?? $val->value }};border:2px solid #eee"></span>
          @else
            <span class="badge border text-dark flex-shrink-0"
                  style="background:#f0f0ff;border-color:#d0d0ff!important;min-width:36px">
              {{ $val->value }}
            </span>
          @endif

          <span class="flex-grow-1" style="font-size:.875rem;color:#566a7f">{{ $val->label }}</span>
          <span class="text-muted" style="font-size:.75rem">order: {{ $val->sort_order }}</span>

          {{-- Inline edit button --}}
          <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                  onclick="toggleEditRow({{ $val->id }})" title="Edit">
            <i class="bi bi-pencil"></i>
          </button>

          {{-- Delete --}}
          <form action="{{ route('admin.filter-values.destroy', $val) }}"
                method="POST" class="d-inline"
                onsubmit="return confirm('Delete karna chahte ho?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger py-0 px-2">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>

        {{-- Inline edit form (hidden by default) --}}
        <div id="edit-row-{{ $val->id }}" style="display:none" class="bg-light rounded p-2 mb-1">
          <form action="{{ route('admin.filter-values.update', $val) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-2 align-items-end">
              <div class="col">
                <input type="text" name="value" class="form-control form-control-sm"
                       value="{{ $val->value }}" placeholder="Value" required>
              </div>
              <div class="col">
                <input type="text" name="label" class="form-control form-control-sm"
                       value="{{ $val->label }}" placeholder="Label" required>
              </div>
              @if($filterType->type === 'color')
              <div class="col-auto">
                <input type="color" name="meta" class="form-control form-control-color form-control-sm"
                       value="{{ $val->meta ?? '#696cff' }}">
              </div>
              @endif
              <div class="col-auto">
                <input type="number" name="sort_order" class="form-control form-control-sm"
                       value="{{ $val->sort_order }}" style="width:60px">
              </div>
              <div class="col-auto d-flex gap-1">
                <button type="submit" class="btn btn-success btn-sm py-0">
                  <i class="bi bi-check-lg"></i>
                </button>
                <button type="button" class="btn btn-secondary btn-sm py-0"
                        onclick="toggleEditRow({{ $val->id }})">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>
          </form>
        </div>

        @empty
        <p class="text-muted text-center py-3 mb-0">
          <i class="bi bi-inbox d-block fs-3 mb-1"></i>
          Koi value nahi — upar se add karo
        </p>
        @endforelse

      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
function toggleEditRow(id) {
  const row  = document.getElementById('val-row-' + id);
  const edit = document.getElementById('edit-row-' + id);
  const isHidden = edit.style.display === 'none';
  edit.style.display = isHidden ? 'block' : 'none';
}
</script>
@endpush