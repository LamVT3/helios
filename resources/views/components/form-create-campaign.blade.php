<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-campaign" action="{{ route("campaign-create") }}">
                <input type="hidden" name="campaign_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Campaign</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-campaign-alert"></div>
                        <section>
                            <label class="label" for="name">Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Campaign name..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="code">Code</label>
                            <label class="input">
                                <input type="text" name="code" class="form-control" id="code"
                                       placeholder="Campaign Code...">
                            </label>
                        </section>
                        <section>
                            <label class="label" for="description">Description</label>
                            <label class="textarea">
                                <textarea name="description" class="form-control" id="description" rows="3"
                                          placeholder="Campaign Description..."></textarea>
                            </label>
                        </section>
                        <section>
                            <label class="label">Active</label>
                            <label class="select">
                                <select name="is_active">
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