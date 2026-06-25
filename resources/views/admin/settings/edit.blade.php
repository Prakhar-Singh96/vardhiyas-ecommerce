@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <h4 class="fw-bold mb-1">Settings</h4>
  <p class="text-muted mb-4">Payment Manage</p>

  @if(session('success'))
  <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-check-circle"></i> {{ session('success') }}
  </div>
  @endif

  <div class="row g-4">

    {{-- ── RAZORPAY SETTINGS ── --}}
    <div class="col-md-6">
      <form action="{{ route('admin.settings.payment') }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
          <div class="card-header border-bottom d-flex align-items-center gap-2">
            <span class="avatar avatar-sm">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-credit-card"></i>
              </span>
            </span>
            <div>
              <h5 class="card-title mb-0">Razorpay Payment</h5>
              <small class="text-muted">Test ya Live API keys daalo</small>
            </div>
          </div>
          <div class="card-body pt-4">

            <label class="switch switch-primary mb-4">
              <input type="checkbox" class="switch-input" name="razorpay_enabled" value="1"
                     {{ $payment->razorpay_enabled ? 'checked' : '' }}>
              <span class="switch-toggle-slider">
                <span class="switch-on"><i class="bx bx-check"></i></span>
                <span class="switch-off"><i class="bx bx-x"></i></span>
              </span>
              <span class="switch-label">Razorpay Enable</span>
            </label>

            <div class="mb-4">
              <label class="form-label fw-semibold">Mode</label>
              <select name="mode" class="form-select">
                <option value="test" {{ $payment->mode == 'test' ? 'selected' : '' }}>Test Mode</option>
                <option value="live" {{ $payment->mode == 'live' ? 'selected' : '' }}>Live Mode</option>
              </select>
              <div class="form-text">Jis mode pe ho, wahi keys use hongi checkout pe</div>
            </div>

            <div class="border rounded p-3 mb-3">
              <p class="fw-semibold mb-2" style="font-size:.85rem">🧪 Test Keys</p>
              <div class="mb-2">
                <label class="form-label" style="font-size:.8rem">Key ID</label>
                <input type="text" name="test_key" class="form-control"
                       placeholder="rzp_test_xxxxxxxx"
                       value="{{ $payment->test_key }}">
              </div>
              <div>
                <label class="form-label" style="font-size:.8rem">Key Secret</label>
                <input type="password" name="test_secret" class="form-control"
                       placeholder="{{ $payment->test_secret ? '••••••••' : 'Secret daalo' }}">
              </div>
            </div>

            <div class="border rounded p-3 mb-3">
              <p class="fw-semibold mb-2" style="font-size:.85rem">🔴 Live Keys</p>
              <div class="mb-2">
                <label class="form-label" style="font-size:.8rem">Key ID</label>
                <input type="text" name="live_key" class="form-control"
                       placeholder="rzp_live_xxxxxxxx"
                       value="{{ $payment->live_key }}">
              </div>
              <div>
                <label class="form-label" style="font-size:.8rem">Key Secret</label>
                <input type="password" name="live_secret" class="form-control"
                       placeholder="{{ $payment->live_secret ? '••••••••' : 'Secret daalo' }}">
              </div>
            </div>

            <label class="switch switch-primary mb-4">
              <input type="checkbox" class="switch-input" name="cod_enabled" value="1"
                     {{ $payment->cod_enabled ? 'checked' : '' }}>
              <span class="switch-toggle-slider">
                <span class="switch-on"><i class="bx bx-check"></i></span>
                <span class="switch-off"><i class="bx bx-x"></i></span>
              </span>
              <span class="switch-label">Cash on Delivery (COD) Enable</span>
            </label>

            <div class="row g-3">
              <div class="col-6">
                <label class="form-label fw-semibold">Shipping Charge (₹)</label>
                <input type="number" name="shipping_charge" class="form-control"
                       value="{{ $payment->shipping_charge }}">
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold">Free Shipping Above (₹)</label>
                <input type="number" name="free_shipping_above" class="form-control"
                       value="{{ $payment->free_shipping_above }}">
              </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> Payment Settings Save Karo
            </button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection