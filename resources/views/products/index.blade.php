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
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
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
                ajax: "{{ route('products.index') }}",
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
            $('body').on('click', '#products-destroy', function() {
                let id = $(this).attr('data-id');
                var url = "{{ route('products.destroy', ':id') }}";
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
