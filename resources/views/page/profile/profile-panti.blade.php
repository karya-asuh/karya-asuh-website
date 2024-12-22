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

  <div class="text-center mt-5">
      <h4>{{ $panti_details[0]->fund }}</h4>
      <form action="/withdraw" method="POST">
        @csrf
        <div class="mb-3 d-flex">
          <label for="payout_fund" class="form-label">Payout Fund:</label>
          <input type="number" name="payout_fund" id="payout_fund" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="detail" class="form-label">Detail:</label>
          <textarea name="detail" id="detail" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Withdraw</button>
      </form>
  </div>
</div>
@endsection