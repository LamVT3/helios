<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-config" action="{{ route("config-create") }}">
                <input type="hidden" name="config_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Config</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-config-alert"></div>
                        <section>
                            <label class="label" for="name">Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Config name..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label require_field" for="key">Key</label>
                            <label class="input">
                                <input type="text" name="key" id="key" class="form-control" placeholder="Config Key..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label require_field" for="value">Value</label>
                            <label class="input">
                                <input type="text" name="value" id="value" class="form-control" placeholder="Config Value..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="description">Description</label>
                            <label class="textarea">
                                <textarea name="description" class="form-control" id="description" rows="3"
                                          placeholder="Config Description..."></textarea>
                            </label>
                        </section>
                        <section>
                            <label class="label">Active</label>
                            <label class="select">
                                <select name="active">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>