<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-campaign" action="{{ route("notification-save") }}">
                <input type="hidden" name="notification_id" value=""/>
                <input type="hidden" name="notification_type" value="Create"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Notification</h3>
                </div>
                <div class="modal-body">

                    @if(auth()->user()->role != "Admin")
                        <p class="text-warning">This user can not permission create a notification.</p>
                    @else
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-campaign-alert"></div>
                        <fieldset>
                            <div class="row" style="margin: 10px -15px">
                                <section>
                                    <label class="label col col-2" for="title">Title</label>
                                    <label class="input col col-10">
                                        <input name="title" id="title" type="text" required placeholder="Enter title"/>
                                    </label>
                                </section>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section>
                                    <label class="label col col-2" for="content">Content</label>
                                    <label class="textarea col col-10">
                                        <textarea name="content" id="textarea_content" required></textarea>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                    </div>
                        @endunless
                </div>
                <div class="modal-footer">
                    @if(auth()->user()->role == "Admin")
                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
