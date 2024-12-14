@extends('welcome')

@section('page')
<div class="container mt-4">
    @if(isset($product))
        <div class="row">
            <div class="col-md-6">
                <img src="{{ $product['image'] }}" class="img-fluid" alt="{{ $product['name'] }}">
            </div>
            <div class="col-md-6">
                <h1>{{ $product['name'] }}</h1>
                <p>Panti: {{ $product['panti']['name'] }}</p>
                <p>{{ $product['description'] }}</p>
                <h3>Price: Rp {{ number_format($product['price'], 0, ',', '.') }}</h3>
                <button class="btn btn-primary btn-lg">Donate/Buy</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h3>Panti Details</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ $product['panti']['image'] }}" class="img-fluid" alt="{{ $product['panti']['name'] }}">
                            </div>
                            <div class="col-md-8">
                                <h5>{{ $product['panti']['name'] }}</h5>
                                <p>Location: {{ $product['panti']['location'] }}</p>
                                <p>{{ $product['panti']['description'] }}</p>
                                <a href="{{ route('panti.detail', $product['panti']['id']) }}" class="btn btn-secondary">View Panti Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            No product information available.
        </div>
    @endif
</div>
@endsection