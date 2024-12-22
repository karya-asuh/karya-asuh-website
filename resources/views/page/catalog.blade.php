@extends('welcome')

@section('page')
<div class="container mt-4">
  <div class="row">
      <div class="col-12">
          <h1>Product Catalog</h1>
          
          <!-- Search Bar -->
          <div class="mb-4">
            <form action="{{ route('catalog') }}" method="GET">
              <input type="text" name="query" class="form-control" placeholder="Search products..." value="{{ request()->query('query') }}">
            </form>
          </div>

          <!-- Product Grid -->
          <div class="row">
              <!-- Product Card (Repeat for multiple products) -->
              @foreach ($creations as $creation)
                @include('component.product-card', ['creation' => $creation])
              @endforeach
              <!-- More product cards -->
          </div>
      </div>
  </div>
</div>
@endsection