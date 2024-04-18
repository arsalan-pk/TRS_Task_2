@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Welcome To The Right Software ')
@section('content_header_title', 'Category')
@section('content_header_subtitle', 'Category Related Products')


@section('content_body')

    <div class="card">
        <div class="card-body">
            <h1>Products in {{ $category->name }}</h1>

            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            @if ($product->images->isNotEmpty())
                                <img src="{{ asset($product->images->first()->url) }}" class="card-img-top"
                                    alt="Product Image">
                            @else
                                <img src="{{ asset('default.png') }}" class="card-img-top" alt="Product Image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>

                                <a href="{{ route('product-detail-page', $product->id) }}" class="btn btn-primary">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@endpush
