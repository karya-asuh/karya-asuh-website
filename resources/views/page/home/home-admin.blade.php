@extends('welcome')

@section('page')
<div class="container">
  <h2>Request for Withdraw</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Panti Name</th>
        <th>Payout Fund</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($withdraws as $withdraw)
      <form action="{{ route('accept-withdraw', $withdraw->withdraw_id) }}" method="POST">
        @csrf
        <tr>
          <td>{{ $withdraw->name }}</td>
          <td>Rp. {{ $withdraw->payout_fund }}</td>
          <td>{{ $withdraw->status }}</td>
          <td>
            @if ($withdraw->status == "Request For Withdraw")
              <button type="submit" class="btn btn-success">Accept</button>
            @endif
          </td>
        </tr>
      </form>
      
      @endforeach
    </tbody>
  </table>
</div>
@endsection