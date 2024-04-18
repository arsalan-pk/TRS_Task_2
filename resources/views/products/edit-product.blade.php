@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Welcome To The Right Software ')
@section('content_header_title', 'Product')
@section('content_header_subtitle', 'Edit Prodct')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('updateProduct') }}" enctype="multipart/form-data" id="updateProduct">
                @csrf
                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                        class="form-control" required>
                    <span class="text-danger name_error" id=""></span>
                </div>

                <div class="form-group">
                    <label for="description">Product Description:</label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                    <span class="text-danger description_error" id=""></span>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}"
                        class="form-control" required>
                    <span class="text-danger price_error" id=""></span>
                </div>

                <div class="form-group">
                    <label for="categories">Categories:</label>
                    <select id="categories" name="categories[]" multiple class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Existing Images:</label>
                    <div class="row">
                        @foreach ($product->images as $image)
                            <div class="mb-3 col-md-3">
                                <img src="{{ asset($image->url) }}" alt="Product Image" class="img-fluid">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="images">New Images:</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                        class="form-control-file">
                    <span class="text-danger images_error" id=""></span>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>


@stop

{{-- Push extra CSS --}}

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.css') }}">
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script src="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script>
        // update script
        $('#updateProduct').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                type: 'post',
                data: new FormData($('#updateProduct')[0]),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforSend: function() {
                    $('.text-danger').text('');
                },
                complete: function() {},
                success: function(data) {
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {

                            if (prefix == 'images.0') {
                                $('span.images_error').text(val[0]);
                            } else {
                                $('span.' + prefix + '_error').text(val[0]);
                            }

                        });
                    } else {
                        $('.text-danger').text('');
                        $.toast({
                            heading: 'Success',
                            text: 'update successfully',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        });
                        window.history.back();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                },
            });

        });
    </script>
@endpush
