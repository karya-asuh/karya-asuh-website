<div class="col-md-3 mb-4">
  <div class="card">
      <img src="{{ $creation->creation_file ? asset('storage/product/'.$creation->creation_file) : asset('empty-image/empty-product.png') }}" class="card-img-top" alt="Product">
      <div class="card-body">
          <h5 class="card-title">{{ $creation->name }}</h5>
          <p class="card-text">Rp. {{ $creation->min_price }}</p>
          <a href="{{ route('product.detail', $creation->creation_id) }}" class="btn btn-primary">View Details</a>
      </div>
  </div>
</div>