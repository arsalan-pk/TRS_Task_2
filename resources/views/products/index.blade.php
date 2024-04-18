@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Index Product')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Product')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Product List</h3>
            <div class="card-tools">
                <a href="{{ route('createProduct') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Product</a>
            </div>
        </div>
        <div class="card-body">
            <table id="Product" class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    {{-- create modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" action="{{ route('storeProduct') }}" method="POST" id="storeProduct"
                        enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="Product_name"
                                            class="col-sm-3 col-form-label">{{ __('Product name') }}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Product_name" name="Product_name"
                                                placeholder="" value="{{ old('Product_name') }}"
                                                autocomplete="Product_name">
                                            <span class="text-danger Product_name_error" id=""></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Store</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- edit modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" action="{{ route('updateProduct') }}" method="POST" id="updateProduct">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="Product_name"
                                            class="col-sm-3 col-form-label">{{ __('Product name') }}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Product_edit" name="Product_name"
                                                placeholder="" value="{{ old('Product_name') }}"
                                                autocomplete="Product_name">
                                            <span class="text-danger Product_name_error" id=""></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="Product_id" id="Product_id">
                        </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.net/responsive.bootstrap.min.css') }}">
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script src="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('vendor/datatable/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/datatable/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('vendor/datatable/datatables.net/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatable/datatables.net/responsive.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {

            let table = $('#Product').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('indexProduct') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        exportable: false,
                        serachable: false,
                        sClass: 'text-center'
                    },
                ],
            });

            // delete script
            $('body').on('click', '#deleteProduct', function() {
                let id = $(this).attr('data-id');
                var url = "{{ route('deleteProduct', ':id') }}";
                url = url.replace(':id', id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3f51b5',
                    cancelButtonColor: '#ff4081',
                    confirmButtonText: 'Great',
                    buttons: {
                        cancel: true,
                        confirm: true,
                    }
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            url: url,
                            type: "delete",
                            method: 'delete',
                            success: function(data) {
                                $('#Product').DataTable().ajax.reload();
                                $.toast({
                                    heading: 'Success',
                                    text: data.message,
                                    showHideTransition: 'slide',
                                    icon: 'success',
                                    loaderBg: '#f96868',
                                    position: 'top-right'
                                });
                            },
                            error: function(err) {
                                $.toast({
                                    heading: 'Danger',
                                    text: 'error:' + err.status,
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    loaderBg: '#f2a654',
                                    position: 'top-right'
                                });
                            }
                        });
                    }

                });
            });
        });
    </script>
@endpush
