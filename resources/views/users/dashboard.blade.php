<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>user home</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"> --}}
    @vite(['resources/scss/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="container">
        <div class="row">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4" style="padding:15px;">
                    <div class="card">
                        <div class="card-body">
                            @if ($product->images->isNotEmpty())
                                <img class="card-img-top" alt="{{ $product->name }}"
                                    src="{{ asset($product->images->first()->url) }}"
                                    style="max-height: 300px; width: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('default.png') }}" class="card-img-top" alt="Product Image">
                            @endif
                            <br />
                            <h2 class="text-end">${{ $product->price }}</h2>
                            <h2><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></h2>
                            <br />
                            <p class="text-justify">{{ $product->description }}</p>
                        </div>
                        <br />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>


</body>

</html>
