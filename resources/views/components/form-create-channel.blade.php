<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-channel" action="{{ route("channel-create") }}">
                <input type="hidden" name="channel_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Channel</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-channel-alert"></div>
                        <section>
                            <label class="label require_field" for="name">Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Channel name..." />
                            </label>
                        </section>
                        <section>
                            <label class="label require_field" for="fb_id">Facebook Account ID</label>
                            <label class="input">
                                <input type="text" name="fb_id" id="fb_id" class="form-control" placeholder="Facebook Account ID..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label" for="thankyou_page">Thank You Page</label>
                            <label class="select">
                                <select name="thankyou_page">
                                    <option value="" selected>Select Thank You Page</option>
                                    @foreach ($thankyou_page as $item)
                                        <option value="{{ $item->id }}">{{ $item->url }}</option>
                                    @endforeach
                                </select>
                                <i></i>
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