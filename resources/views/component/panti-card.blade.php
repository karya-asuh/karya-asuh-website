<div class="col-md-4 mb-4">
  <div class="card">
      <img src="{{ $panti->user_image ? asset('storage/profile/'.$panti->user_image) : asset('empty-image/empty-profile.png') }}" class="card-img-top" alt="Panti">
      <div class="card-body">
          <h5 class="card-title">{{ $panti->name }}</h5>
          <p class="card-text">Location: {{ $panti->location }}</p>
          {{-- <a href="{{ route('panti.detail', 1) }}" class="btn btn-primary">View Details</a> --}}
      </div>
  </div>
</div>