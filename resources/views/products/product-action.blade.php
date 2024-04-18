@can('edit products')
    <a href="{{ route('editProduct', $id) }}" data-id="{{ $id }}"><i class="fas fa-edit" aria-hidden="true"></i></a>
@endcan
@can('delete products')
    <a href="javascript:void(0)" id="deleteProduct" data-id="{{ $id }}" style="color:red;"><i class="fa fa-trash"
            aria-hidden="true"></i></a>
@endcan
