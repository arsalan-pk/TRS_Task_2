@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Welcome To The Right Software ')
@section('content_header_title', 'Prodcut')
@section('content_header_subtitle', 'Add New Product')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('storeProduct') }}" enctype="multipart/form-data" id="storeProduct">
                @csrf
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name">
                    <span class="text-danger name_error" id=""></span>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Enter product description"></textarea>
                    <span class="text-danger description_error" id=""></span>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price"
                        placeholder="Enter product price">
                    <span class="text-danger price_error" id=""></span>
                </div>
                <div class="form-group">
                    <label for="categories">Categories</label>
                    <select multiple class="form-control" id="categories" name="categories[]">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="images">Images</label>
                    <input type="file" class="form-control-file" id="images" name="images[]" multiple>
                    <span class="text-danger images_error" id="images_error"></span>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // store script
        $('#storeProduct').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                type: 'post',
                data: new FormData($('#storeProduct')[0]),
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
                                $('span.images_error').text(val);
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
