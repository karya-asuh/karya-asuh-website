@extends('welcome')

@section('page')
<div class="container mt-4">
  <div class="row">
      <div class="col-12">
          <h1>Product Catalog</h1>
          
          <!-- Search Bar -->
          <div class="mb-4">
              <input type="text" class="form-control" placeholder="Search products...">
          </div>

          <!-- Product Grid -->
          <div class="row">
              <!-- Product Card (Repeat for multiple products) -->
              @include('component.product-card')
              <!-- More product cards -->
          </div>
      </div>
  </div>
</div>
@endsection