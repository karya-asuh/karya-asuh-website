@extends('welcome')

@section('page')
  <h1>Welcome, {{ $user->name }}</h1>
  <p>Email: {{ $user->email }}</p>
  <p>Role: {{ $user->role }}</p>
  <form action="/logout" method="POST">
      @csrf
      <button type="submit">Logout</button>
  </form>
  @if($user->role == 'donor')
    {{-- Write something here for donor --}}
  @elseif ($user->role == 'panti')
    {{-- Write something here for panti --}}
  @endif
@endsection