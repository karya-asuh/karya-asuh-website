@extends('welcome')

@section('page')
<div class="container mt-4">
    @if(isset($panti))
        <div class="row">
            <div class="col-md-6">
                <img src="{{ $panti['image'] }}" class="img-fluid" alt="{{ $panti['name'] }}">
            </div>
            <div class="col-md-6">
                <h1>{{ $panti['name'] }}</h1>
                <p>Location: {{ $panti['location'] }}</p>
                <p>{{ $panti['description'] }}</p>
                <button class="btn btn-primary btn-lg">Donate to Panti</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h3>Panti Products</h3>
                <div class="row">
                    @foreach($panti['products'] as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product['name'] }}</h5>
                                <p class="card-text">Price: Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                                <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            No panti information available.
        </div>
    @endif
</div>
@endsection