<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="close" id="hideModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" action="" method="POST" id="modal-form">
                    <input type="hidden" name="_method" value="" id="_method">
                    @csrf
                    <input type="hidden" name="category_id" id="category_id">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="category_name"
                                        class="col-sm-3 col-form-label">{{ __('category name') }}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="category_name"
                                            name="category_name" placeholder="name" value="">
                                        <span class="text-danger category_name_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="hideModal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit-btn"></button>
                </form>
            </div>
        </div>
    </div>
</div>
