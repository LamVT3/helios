<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-import-contact" class="form-horizontal" action="{{ route("contacts.import") }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Import Contact</h3>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div id="form-source-alert"></div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Input File</label>
                        <div class="col-md-10">
                            <input type="file" name="import" class="btn btn-default" id="import">

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            Sample Files: <a href="{{ asset('sample/import_contacts.xlsx') }}" target="_blank">import_contact.xlsx</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="import_contact" type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fa fa-upload"></i>
                        Import
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>