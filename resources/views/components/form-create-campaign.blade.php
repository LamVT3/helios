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
                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select>
                                            <option>Create New Campaign</option>
                                            <option>Choose Existing Campaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="name">Campaign Name</label>
                                    <label class="input col col-9">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter a campaign name..."/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="description">Description</label>
                                    <label class="textarea col col-9">
                                        <textarea name="description" class="form-control" id="description" rows="3"
                                                  placeholder="Enter a campaign description..."></textarea>
                                    </label>
                                </section>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select>
                                            <option>Create New Subcampaign</option>
                                            <option>Choose Existing Subcampaign</option>
                                            <option>Skip Subcampaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="name">Subcampaign Name</label>
                                    <label class="input col col-9">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter a subcampaign name..."/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="description">Description</label>
                                    <label class="textarea col col-9">
                                        <textarea name="description" class="form-control" id="description" rows="3"
                                                  placeholder="Enter a subcampaign description..."></textarea>
                                    </label>
                                </section>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select>
                                            <option>Create New Ad</option>
                                            <option>Skip Ad</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="name">Ad Name</label>
                                    <label class="input col col-9">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter an ad name..."/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="description">Ad Description</label>
                                    <label class="textarea col col-9">
                                        <textarea name="description" class="form-control" id="description" rows="3"
                                                  placeholder="Enter an ad description..."></textarea>
                                    </label>
                                </section>
                            </div>
                        </fieldset>

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