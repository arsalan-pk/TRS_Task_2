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
    {{-- create modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" action="{{ route('categories.store') }}" method="POST" id="storeCategory">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="category_name"
                                            class="col-sm-3 col-form-label">{{ __('category name') }}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="category_name"
                                                name="category_name" placeholder="" value="{{ old('category_name') }}"
                                                autocomplete="category_name">
                                            <span class="text-danger category_name_error" id=""></span>
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
    {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" action="{{ route('categories.update') }}" method="POST" id="updateCategory">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="category_name"
                                            class="col-sm-3 col-form-label">{{ __('category name') }}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="category_edit"
                                                name="category_name" placeholder="" value="{{ old('category_name') }}"
                                                autocomplete="category_name">
                                            <span class="text-danger category_name_error" id=""></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="category_id" id="category_id">
                        </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
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
            // dom = responsiveDataTables();
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

            // create script
            $('body').on('click', '#createCategory', function() {
                $('#createModal').modal('show');
                $('#storeCategory')[0].reset()
            });
            // store script
            $('#storeCategory').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    type: 'post',
                    data: new FormData($('#storeCategory')[0]),
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
                            $('#createModal').modal('hide');

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
            $('body').on('click', '#editCategory', function() {
                let id = $(this).attr('data-id');
                $.get("{{ route('categories.edit',"+id+") }}", {
                        'id': id
                    },
                    function(data) {
                        $('#editModal').modal('show');
                        $('#category_edit').val(data.name);
                        $('#category_id').val(data.id);
                    })
            });

            // update script
            $('#updateCategory').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    type: 'post',
                    data: new FormData($('#updateCategory')[0]),
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
                            $('#editModal').modal('hide');

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
            $('body').on('click', '#deleteCategory', function() {
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
