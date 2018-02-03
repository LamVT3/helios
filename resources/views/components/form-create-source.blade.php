<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-source" action="{{ route("source-create") }}">
                <input type="hidden" name="source_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Source</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-source-alert"></div>
                        <section>
                            <label class="label" for="name">Source Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Source name..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="description">Source Description</label>
                            <label class="textarea">
                                <textarea name="description" class="form-control" id="description" rows="3"
                                          placeholder="Source Description..."></textarea>
                            </label>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Create Source
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>