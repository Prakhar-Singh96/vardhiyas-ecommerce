{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ $order->order_number }}</h4>
      <p class="text-muted mb-0">{{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-1"></i> Back
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success mb-4">{{ session('success') }}</div>
  @endif

  <div class="row g-4">

    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header border-bottom"><h5 class="mb-0">Items</h5></div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead><tr><th>Product</th><th>Size/Color</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
            <tbody>
              @foreach($order->items as $item)
              <tr>
                <td class="d-flex align-items-center gap-2">
                  @if($item->image_path)
                  <img src="{{ asset('storage/'.$item->image_path) }}"
                       style="width:44px;height:44px;object-fit:cover;border-radius:6px">
                  @endif
                  {{ $item->product_name }}
                </td>
                <td>{{ $item->size_label }} {{ $item->color_label ? '/ '.$item->color_label : '' }}</td>
                <td>₹{{ number_format($item->price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>₹{{ number_format($item->subtotal) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between"><span>Subtotal</span><span>₹{{ number_format($order->subtotal) }}</span></div>
          <div class="d-flex justify-content-between"><span>Shipping</span><span>₹{{ number_format($order->shipping_charge) }}</span></div>
          <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>₹{{ number_format($order->total) }}</span></div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-header border-bottom"><h5 class="mb-0">Customer & Address</h5></div>
        <div class="card-body">
          <p class="mb-1 fw-semibold">{{ $order->customer_name }}</p>
          <p class="mb-1">{{ $order->customer_phone }}</p>
          <p class="mb-3 text-muted">{{ $order->customer_email }}</p>
          <p class="mb-0">
            {{ $order->address_line }}@if($order->locality), {{ $order->locality }}@endif<br>
            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
          </p>
        </div>
      </div>

      <div class="card">
        <div class="card-header border-bottom"><h5 class="mb-0">Update Status</h5></div>
        <div class="card-body">
          <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf @method('PATCH')
            <select name="status" class="form-select mb-3">
              @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $st)
              <option value="{{ $st }}" {{ $order->status == $st ? 'selected' : '' }}>
                {{ ucfirst($st) }}
              </option>
              @endforeach
            </select>
            <button class="btn btn-primary w-100">Update</button>
          </form>
          @if($order->whatsapp_sent_at)
          <p class="text-success mt-3 mb-0" style="font-size:.8rem">
            <i class="bx bx-check-circle"></i> WhatsApp bheja gaya: {{ $order->whatsapp_sent_at->format('d M, h:i A') }}
          </p>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection