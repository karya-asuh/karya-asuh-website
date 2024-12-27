@extends('welcome')

@section('page')
<div class="container mt-4">
    @if(isset($creation[0]))
        <div class="row">
            <div class="col-md-6">
                <img src="{{ $creation[0]->creation_file ? asset('storage/product/'.$creation[0]->creation_file) : asset('empty-image/empty-product.png') }}" class="img-fluid" alt="product">
            </div>
            <div class="col-md-6">
                <h1>{{ $creation[0]->name }}</h1>
                <p>Panti: {{ $creation[0]->panti_name }}</p>
                <p>{{ $creation[0]->desc }}</p>
                <h3>Min Price: Rp {{ number_format($creation[0]->min_price, 0, ',', '.') }}</h3>
                <form action="{{ route('generate-snap-token', $creation[0]->creation_id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="custom_price">Custom Price</label>
                        <input type="number" id="custom_price" name="custom_price" class="form-control" placeholder="Enter custom price" min="{{ $creation[0]->min_price }}" value="{{ old('custom_price') }}">
                        @error('custom_price')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" id="pay-button">Donate/Buy</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h3>Panti Details</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ $creation[0]->panti_image ? asset('storage/profile/'.$creation[0]->panti_image) : asset('empty-image/empty-profile.png') }}" class="img-fluid" alt="panti">
                            </div>
                            <div class="col-md-8">
                                <h5>{{ $creation[0]->panti_name }}</h5>
                                <p>Location: {{ $creation[0]->panti_location }}</p>
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
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env("MIDTRANS_CLIENT_KEY") }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(event) {
        event.preventDefault();  // Prevent the form from submitting normally

        var customPrice = document.getElementById('custom_price').value;

        if (customPrice < {{ $creation[0]->min_price }}) {
            alert('Price must be at least the minimum price.');
            return;
        }

        // Send AJAX request to get Snap Token
        fetch('{{ route('generate-snap-token', $creation[0]->creation_id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                custom_price: customPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                // Show the payment popup using Midtrans Snap
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    onPending: function(result) {
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    onError: function(result) {
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    }
                });
            } else {
                alert('Error generating payment token');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating payment token');
        });
    };
</script>
@endsection