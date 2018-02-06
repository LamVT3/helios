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

                    @unless(auth()->user()->team_id)
                        <p class="text-warning">This user can not create a campaign because it hasn't been assigned to a team.</p>
                    @else
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-campaign-alert"></div>
                        <fieldset>
                            <div class="row" style="margin: 10px -15px">
                                <section>
                                    <strong class="col col-3">Team</strong>
                                    <div class="col col-9">{{ auth()->user()->team_name }}</div>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="source">Source</label>
                                    <label class="select col col-8">
                                        <select name="source" id="source">
                                            @foreach($team->sources as $item)
                                                <option value="{{ $item['source_id'] or '' }}">{{ $item['source_name'] }}</option>
                                                @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select name="campaign_type" id="campaign-type">
                                            <option value="new">Create New Campaign</option>
                                            <option value="old">Choose Existing Campaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-campaign">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="campaign_name">Campaign Name</label>
                                        <label class="input col col-9">
                                            <input type="text" name="campaign_name" id="campaign_name"
                                                   class="form-control"
                                                   placeholder="Enter a campaign name..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                            <div id="old-campaign" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="campaign">Use existing</label>
                                        <label class="col col-9">
                                            <input type="text" name="campaign" id="campaign" class=""
                                                   placeholder="Choose a campaign..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select name="subcampaign_type" id="subcampaign-type">
                                            <option value="new">Create New Subcampaign</option>
                                            <option value="old" disabled>Choose Existing Subcampaign</option>
                                            <option value="skip" selected>Skip Subcampaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-subcampaign" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="subcampaign_name">Subcampaign Name</label>
                                        <label class="input col col-9">
                                            <input type="text" name="subcampaign_name" id="subcampaign_name"
                                                   class="form-control"
                                                   placeholder="Enter a subcampaign name..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                            <div id="old-subcampaign" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="subcampaign">Use existing</label>
                                        <label class="col col-9">
                                            <input type="text" name="subcampaign" id="subcampaign" class=""
                                                   placeholder="Choose a subcampaign..."/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <strong class="text-right col col-3">Description</strong>
                                    <div class="col col-9 subc-desc">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select name="select_ad" id="select-ad">
                                            <option value="new">Create New Ad</option>
                                            <option value="skip" selected>Skip Ad</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-ad" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="ad_name">Ad Name</label>
                                        <label class="input col col-9">
                                            <input type="text" name="ad_name" id="ad_name" class="form-control"
                                                   placeholder="Enter an ad name..."/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="medium">Medium</label>
                                        <label class="input col col-9">
                                            <input type="text" name="medium" id="medium" class="form-control"
                                                   placeholder="Enter a medium. Ex: Conversion"/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="landing_page">Landing Page</label>
                                        <label class="select col col-9">
                                            <select name="landing_page" id="landing_page">
                                                @foreach ($landing_pages as $item)
                                                    <option value="{{ $item->id }}">{{ $item->url }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                        @endunless
                </div>
                <div class="modal-footer">
                    <span class="loading pull-left" style="display:none">
                        <img id="img_ajax_upload" src="http://helios.com/img/loading/rolling.gif" alt="" style="float:left; width: 20%;">
                    </span>
                    @if(auth()->user()->team_id)
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