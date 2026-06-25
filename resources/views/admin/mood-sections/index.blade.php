@extends('admin.layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Match The Mood</h4>
      <p class="text-muted mb-0">
        Homepage ka "Match The Mood" section — max 4 cards
      </p>
    </div>
    <a href="{{ route('admin.mood-sections.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> Add Mood Card
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Info box --}}
  <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
    <i class="bx bx-info-circle fs-5"></i>
    <div>
      <strong>Kaise kaam karta hai?</strong><br>
      Har card pe ek image hoti hai (portrait) + 2 lines text (upar chota, neeche bada).
      Category select karo — click pe woh category ka product listing page khulega.
    </div>
  </div>

  <div class="row g-3">
    @forelse($moodSections as $card)
    <div class="col-md-3">
      <div class="card h-100">
        {{-- Preview --}}
        <div class="position-relative overflow-hidden"
             style="aspect-ratio:3/4;border-radius:8px 8px 0 0">
          <img src="{{ asset('storage/'.$card->image) }}"
               style="width:100%;height:100%;object-fit:cover">
          {{-- Label overlay --}}
          <div style="position:absolute;bottom:0;left:0;right:0;
                      background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 70%);
                      padding:28px 14px 14px">
            @if($card->label_top)
            <div style="font-size:11px;font-weight:600;color:rgba(255,255,255,.85);
                        text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">
              {{ $card->label_top }}
            </div>
            @endif
            @if($card->label_main)
            <div style="font-size:18px;font-weight:900;color:white;
                        text-transform:uppercase;letter-spacing:.5px">
              {{ $card->label_main }}
            </div>
            @endif
          </div>
          {{-- Active badge --}}
          <div style="position:absolute;top:10px;right:10px">
            <label class="switch switch-sm switch-primary">
              <input type="checkbox" class="switch-input toggle-mood"
                     data-url="{{ route('admin.mood-sections.toggle', $card) }}"
                     {{ $card->is_active ? 'checked' : '' }}>
              <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
              </span>
            </label>
          </div>
        </div>

        <div class="card-body p-3">
          {{-- Category link info --}}
          <div class="mb-2">
            @if($card->category)
              <span class="badge bg-label-success rounded-pill">
                <i class="bx bx-link me-1"></i>
                {{ $card->category->name }}
              </span>
              <div style="font-size:.75rem;color:#aaa;margin-top:4px">
                → /collections/{{ $card->category->slug }}
              </div>
            @elseif($card->custom_url)
              <span class="badge bg-label-info rounded-pill">
                <i class="bx bx-link me-1"></i> Custom URL
              </span>
              <div style="font-size:.75rem;color:#aaa;margin-top:4px">
                → {{ $card->custom_url }}
              </div>
            @else
              <span class="badge bg-label-warning rounded-pill">No link set</span>
            @endif
          </div>

          <div style="font-size:.8rem;color:#888">
            Sort: {{ $card->sort_order }}
          </div>
        </div>

        <div class="card-footer border-top p-2 d-flex gap-1">
          <a href="{{ route('admin.mood-sections.edit', $card) }}"
             class="btn btn-sm btn-outline-primary flex-fill">
            <i class="bx bx-edit-alt me-1"></i> Edit
          </a>
          <form action="{{ route('admin.mood-sections.destroy', $card) }}"
                method="POST" class="d-inline"
                onsubmit="return confirm('Delete karna chahte ho?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
              <i class="bx bx-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-image fs-1 text-muted d-block mb-3"></i>
          <h6 class="text-muted">Koi mood card nahi</h6>
          <p class="text-muted" style="font-size:.875rem">
            4 mood cards banao — har ek ek category se link hogi
          </p>
          <a href="{{ route('admin.mood-sections.create') }}"
             class="btn btn-primary btn-sm mt-2">
            <i class="bx bx-plus me-1"></i> Pehla Card Banao
          </a>
        </div>
      </div>
    </div>
    @endforelse
  </div>

</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.toggle-mood').forEach(t => {
  t.addEventListener('change', function() {
    const el = this;
    fetch(this.dataset.url, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(r => r.json())
    .then(d => { if (!d.success) el.checked = !el.checked; })
    .catch(() => { el.checked = !el.checked; });
  });
});
</script>
@endsection