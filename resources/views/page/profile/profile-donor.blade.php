@extends('welcome')

@section('page')
<div class="container mt-5">
  <div class="d-flex align-items-center">
    <img src="{{ Auth::user()->user_image ? asset('storage/profile/'.Auth::user()->user_image) : asset('empty-image/empty-profile.png') }}" alt="Panti Image" class="img-fluid rounded">
      <div class="ms-4">
          <h2>{{ Auth::user()->name }}</h2>
          <p>{{ Auth::user()->username }}</p>
          <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
          </form>
        </form>
      </div>
  </div>

  <div class="mt-5">
      <h4>Detail Pesanan</h4>
      @foreach ($creations as $creation)
      <div class="card p-3" style="background-color: #e0e0e0;">
          <div class="row align-items-center">
              <div class="col-md-2">
                <img src="{{ $creation->creation_file ? asset('storage/product/'.$creation->creation_file) : asset('empty-image/empty-product.png') }}" class="img-fluid" alt="Product">
              </div>
              <div class="col-md-10">
                  <h5>{{ $creation->name }}</h5>
                  <p>{{ $creation->desc }}</p>
              </div>
          </div>
      </div>
      @endforeach
  </div>
</div>
@endsection