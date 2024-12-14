@extends('welcome')

@section('page')
<div class="container mt-4">
  <div class="row">
      <div class="col-12">
          <h1>Panti Asuhan</h1>
          
          <!-- Search Bar -->
          <div class="mb-4">
              <input type="text" class="form-control" placeholder="Search panti...">
          </div>

          <!-- Panti Grid -->
          <div class="row">
              <!-- Panti Card (Repeat for multiple panti) -->
              @include('component.panti-card')
              <!-- More panti cards -->
          </div>
      </div>
  </div>
</div>
@endsection