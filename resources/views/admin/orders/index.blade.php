@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <h4 class="fw-bold mb-1">Orders</h4>
  <p class="text-muted mb-4">Sare customer orders yahan dikhenge</p>

  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-3">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control"
                 placeholder="Order #, Name, Phone se search karo"
                 value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $st)
            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
              {{ ucfirst($st) }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
          <tr>
            <td><span class="fw-semibold">{{ $order->order_number }}</span></td>
            <td>
              {{ $order->customer_name }}<br>
              <small class="text-muted">{{ $order->customer_phone }}</small>
            </td>
            <td>{{ $order->items->count() }} items</td>
            <td class="fw-semibold">₹{{ number_format($order->total) }}</td>
            <td>
              <span class="badge bg-label-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                {{ ucfirst($order->payment_status) }}
              </span><br>
              <small class="text-muted">{{ strtoupper($order->payment_method) }}</small>
            </td>
            <td>
              <span class="badge bg-label-{{ $order->status_badge }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>
            <td><small>{{ $order->created_at->format('d M, h:i A') }}</small></td>
            <td>
              <a href="{{ route('admin.orders.show', $order) }}"
                 class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                <i class="bx bx-show"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">Koi order nahi</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $orders->links() }}</div>
  </div>
</div>
@endsection