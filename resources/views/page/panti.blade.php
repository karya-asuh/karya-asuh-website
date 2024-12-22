@extends('welcome')

@section('page')
<div class="container mt-4">
  <div class="row">
      <div class="col-12">
          <h1>Panti Asuhan</h1>
          
          <!-- Search Bar -->
          <div class="mb-4">
              <form action="{{ route('panti') }}" method="GET">
                <input type="text" name="query" class="form-control" placeholder="Search panti..." value="{{ request()->query('query') }}">
              </form>
          </div>

          <!-- Panti Grid -->
          <div class="row">
              <!-- Panti Card (Repeat for multiple panti) -->
              @foreach ($pantis as $panti)
                @include('component.panti-card', ['panti' => $panti])
              @endforeach
              <!-- More panti cards -->
          </div>
      </div>
  </div>
</div>
@endsection