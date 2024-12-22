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
      </div>
  </div>
</div>
@endsection