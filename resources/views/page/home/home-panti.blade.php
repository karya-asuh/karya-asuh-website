@extends('welcome')

@section('page')
<div class="container mt-4">
  <!-- Header Section -->
  <div class="row align-items-center mb-4">
      <div class="col-md-4 text-center">
          <img src="{{ $panti[0]->user_image ? asset('storage/profile/'.$panti[0]->user_image) : asset('empty-image/empty-profile.png') }}" alt="Panti Image" class="img-fluid rounded">
      </div>
      <div class="col-md-8">
          <h1>{{ $panti[0]->name }}</h1>
          <p>Lokasi: {{ $panti[0]->location }}</p>
          <a href="{{ route('add-creation') }}" class="btn btn-success">Tambah Karya</a>
      </div>
  </div>

  <!-- Karya Panti Section -->
  <div class="row">
      <div class="col-12">
          <h2 class="mb-3">Karya Panti</h2>
          <div class="row">
              @foreach ($creations as $creation)
                  @include('component.product-card', ['creation' => $creation])
              @endforeach
          </div>
      </div>
  </div>
</div>
@endsection