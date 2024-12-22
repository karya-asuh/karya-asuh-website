@extends('welcome')

@section('page')
<div class="container mt-4">
  <!-- Banner -->
  <div class="row">
      <div class="col-12">
          <div class="card bg-primary text-white text-center py-5">
              <div class="card-body">
                  <h1>Donate and Support Panti Asuhan</h1>
                  <p>Make a difference in children's lives</p>
                  <a href="{{ route('panti') }}" class="btn btn-light">Donate Now</a>
              </div>
          </div>
      </div>
  </div>

  <!-- Products Section -->
  <div class="row mt-4">
      <div class="col-12">
          <h2 class="mb-3">Our Products</h2>
          <div class="row">
              <!-- Product Card (Repeat for multiple products) -->
              @foreach ($creations as $creation)
                @include('component.product-card', ['creation' => $creation])
              @endforeach
              <!-- Repeat product cards -->
          </div>
      </div>
  </div>

  <!-- Panti Section -->
  <div class="row mt-4">
      <div class="col-12">
          <h2 class="mb-3">Our Panti Asuhan</h2>
          <div class="row">
              <!-- Panti Card (Repeat for multiple panti) -->
              @foreach ($pantis as $panti)
                @include('component.panti-card', ['panti' => $panti])
              @endforeach
              <!-- Repeat panti cards -->
          </div>
      </div>
  </div>
</div>
@endsection