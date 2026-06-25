@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Filter Types</h4>
      <p class="text-muted mb-0">Size, Color, Stock jaise filters manage karo</p>
    </div>
    <a href="{{ route('admin.filter-types.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> New Filter Type
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  @if($filterTypes->count() > 0)
  <div class="row g-4">
    @foreach($filterTypes as $ft)
    @php
      $iconClass = match($ft->type) {
        'color'   => 'bx-palette',
        'boolean' => 'bx-toggle-right',
        'range'   => 'bx-slider-alt',
        default   => 'bx-list-ul',
      };
      $labelColor = match($ft->type) {
        'color'   => 'warning',
        'boolean' => 'success',
        'range'   => 'info',
        default   => 'primary',
      };
    @endphp
    <div class="col-md-6 col-xl-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between border-bottom py-3">
          <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm">
              <span class="avatar-initial rounded bg-label-{{ $labelColor }}">
                <i class="bx {{ $iconClass }}"></i>
              </span>
            </div>
            <div>
              <span class="fw-semibold d-block" style="line-height:1.2">
                {{ $ft->display_name }}
              </span>
              <small class="text-muted">{{ ucfirst($ft->type) }} type</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-1">
            <span class="badge bg-label-secondary rounded-pill">
              {{ $ft->values_count }} values
            </span>
            <a href="{{ route('admin.filter-types.edit', $ft) }}"
               class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
              <i class="bx bx-edit-alt"></i>
            </a>
            <form action="{{ route('admin.filter-types.destroy', $ft) }}"
                  method="POST" class="d-inline"
                  onsubmit="return confirm('Delete karna chahte ho?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-icon btn-text-danger rounded-pill">
                <i class="bx bx-trash"></i>
              </button>
            </form>
          </div>
        </div>

        <div class="card-body py-3">
          @if($ft->values->count() > 0)
          <div class="d-flex flex-wrap gap-2">
            @foreach($ft->values->take(12) as $val)
              @if($ft->type === 'color')
              <span class="d-flex align-items-center gap-1" title="{{ $val->label }}">
                <span style="width:18px;height:18px;border-radius:50%;
                             background:{{ $val->meta ?? $val->value }};
                             border:2px solid #e0e0e0;display:inline-block;
                             flex-shrink:0"></span>
                <small class="text-muted" style="font-size:.75rem">{{ $val->label }}</small>
              </span>
              @else
              <span class="badge bg-label-{{ $labelColor }}" style="font-size:.75rem">
                {{ $val->label }}
              </span>
              @endif
            @endforeach
            @if($ft->values->count() > 12)
              <span class="text-muted" style="font-size:.78rem;align-self:center">
                +{{ $ft->values->count() - 12 }} more
              </span>
            @endif
          </div>
          @else
          <div class="text-center py-2">
            <p class="text-muted mb-0" style="font-size:.85rem">
              Koi value nahi —
              <a href="{{ route('admin.filter-types.edit', $ft) }}">values add karo</a>
            </p>
          </div>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>

  @else
  <div class="card">
    <div class="card-body text-center py-6">
      <img src="https://cdn-icons-png.flaticon.com/128/7486/7486744.png"
           width="72" class="mb-3 opacity-50">
      <h6 class="text-muted mb-1">Koi filter type nahi hai.</h6>
      <a href="{{ route('admin.filter-types.create') }}" class="btn btn-primary btn-sm mt-2">
        <i class="bx bx-plus me-1"></i> Pehla filter banao
      </a>
    </div>
  </div>
  @endif

</div>
@endsection