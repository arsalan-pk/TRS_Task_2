@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Index Category')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Category')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Category List</h3>
            <div class="card-tools">
                <a href="javascript:void(0)" id="createCategory" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Category</a>
            </div>
        </div>
        <div class="card-body">
            <table id="Category" class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    @include('categories.modal')
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
            let table = $('#Category').DataTable({
                ajax: "{{ route('categories.index') }}",
                processing: true,
                serverSide: true,
                scrollX: false,
                responsive: true,
                autoWidth: false,
                stateSave: true,

                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'product',
                        name: 'product'
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
            // to hide the modal
            $('body').on('click', '#hideModal', function() {
                $('#categories-update').attr('id', 'modal-form');
                $('#categories-store').attr('id', 'modal-form');
                $('#modal').modal('hide');
            });

            // create script
            $('body').on('click', '#createCategory', function() {
                $('#modal').modal('show');
                $('#modalLabel').text('Categories Create');
                $('#modal-form').attr('id', 'categories-store');
                $('#_method').attr('value', "POST");
                $('#categories-store').attr('action', '{{ route('categories.store') }}');
                $('#submit-btn').text('Save');
                $('#categories-store').trigger('reset');
                $('.text-danger').text('');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // store script
            $('body').on('submit', '#categories-store', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    type: 'post',
                    data: new FormData($('#categories-store')[0]),
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
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {

                            $('.text-danger').text('');
                            $('#modal').modal('hide');
                            $('#categories-store').attr('id', 'modal-form');
                            $.toast({
                                heading: 'Success',
                                text: 'created  Successfully',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });

                            $('#Category').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    },
                });

            });

            // edit script
            $('body').on('click', '#categories-edit', function() {
                let id = $(this).attr('data-id');
                var url = "{{ route('categories.edit', ':id') }}";
                $.get(url.replace(':id', id),
                    function(data) {
                        $('#modal').modal('show');
                        $('#modalLabel').text('Categories Update');
                        $('#modal-form').attr('id', 'categories-update');
                        $('#categories-update').trigger("reset");
                        $('#_method').attr('value', "PUT");
                        var url = "{{ route('categories.update', ':id') }}";
                        $('#categories-update').attr('action', url.replace(':id', id));
                        $('#submit-btn').text('Update');
                        $('#categories-update')[0].reset()
                        $('#category_name').attr('value', data.name);
                        $('#category_id').val(data.id);
                    })
            });

            // update script
            $('body').on('submit', '#categories-update', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: new FormData($('#categories-update')[0]),
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
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#categories-update').attr('id', 'modal-form');
                        } else {
                            $('.text-danger').text('');
                            $('#modal').modal('hide');
                            $('#categories-update').attr('id', 'modal-form');
                            $.toast({
                                heading: 'Success',
                                text: 'update successfully',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });
                            $('#Category').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    },
                });

            });

            // delete script
            $('body').on('click', '#categories-destroy', function() {
                let id = $(this).attr('data-id');
                var url = "{{ route('categories.destroy', ':id') }}";
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
                                $('#Category').DataTable().ajax.reload();

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
