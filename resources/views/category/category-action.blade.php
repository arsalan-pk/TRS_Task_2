@can('edit category')
    <a href="javascript:void(0)" id="editCategory" data-id="{{ $id }}"><i class="fas fa-edit"
            aria-hidden="true"></i></a>
@endcan
@can('delete category')
    <a href="javascript:void(0)" id="deleteCategory" data-id="{{ $id }}" style="color:red;"><i class="fa fa-trash"
            aria-hidden="true"></i></a>
@endcan
