<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-ads" action="{{ route("ads-create") }}">
                <input type="hidden" name="ads_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Ads</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-ads-alert"></div>
                        <section>
                            <label class="label" for="name">Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Ads name..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="landing_page">Landing Page</label>
                            <label class="select">
                                <select name="landing_page" id="landing_page">
                                    @foreach ($landing_pages as $item)
                                        <option value="{{ $item->id }}">{{ $item->url }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="keyword">Keyword</label>
                            <label class="input">
                                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Ads keyword..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="description">Description</label>
                            <label class="textarea">
                                <textarea name="description" class="form-control" id="description" rows="3"
                                          placeholder="Ads Description..."></textarea>
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