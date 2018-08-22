<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-campaign" action="{{ route("campaign-create") }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Ads</h3>
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
                                    <strong class="col col-4">Team</strong>
                                    <div class="col col-8">{{ auth()->user()->team_name }}</div>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-4" for="source">Source</label>
                                    <label class="select col col-8">
                                        <select name="source" id="source">
                                            @foreach($team->sources as $item)
                                                <option value="{{ $item['source_id'] or '' }}" {{ $campaign->source_id == $item['source_id'] ? "selected" : "" }}>{{ $item['source_name'] }}</option>
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
                                            <option value="old" selected>Choose Existing Campaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-campaign" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="campaign_name">Campaign Name</label>
                                        <label class="input col col-8">
                                            <input type="text" name="campaign_name" id="campaign_name"
                                                   class="form-control"
                                                   placeholder="Enter a campaign name..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                            <div id="old-campaign">
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="campaign">Use existing</label>
                                        <label class="col col-8">
                                            <input type="text" name="campaign" id="campaign" class=""
                                                   value="{{ $campaign->id }}"
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
                                            <option value="old" selected>Choose Existing Subcampaign</option>
                                            <option value="skip">Skip Subcampaign</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-subcampaign" style="display:none">
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="subcampaign_name">Subcampaign Name</label>
                                        <label class="input col col-8">
                                            <input type="text" name="subcampaign_name" id="subcampaign_name"
                                                   class="form-control" autofocus
                                                   placeholder="Enter a subcampaign name..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                            <div id="old-subcampaign">
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="subcampaign">Use existing</label>
                                        <label class="col col-8">
                                            <input type="text" name="subcampaign" id="subcampaign" class=""
                                                   value="{{ $subcampaign->id }}"
                                                   placeholder="Choose a subcampaign..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select name="select_ad" id="select-ad">
                                            <option value="new">Create New Ad</option>
                                            <option value="skip">Skip Ad</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div id="new-ad">
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="ad_name">Ad Name</label>
                                        <label class="input col col-8">
                                            <input type="text" name="ad_name" id="ad_name" class="form-control"
                                                   autofocus
                                                   placeholder="Enter an ad name..."/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-4" for="medium">Medium</label>
                                        <label class="input col col-8">
                                            <input type="text" name="medium" id="medium" class="form-control"
                                                   placeholder="Enter a medium. Ex: Conversion"/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="mol-link-tracking">MOL link tracking</label>
                                        <label class="input col col-8">
                                            <input type="text" name="mol_link_tracking" id="mol_link_tracking" class="form-control"
                                                   placeholder="Ex: id_landingpage=265&code_chanel=BR16_Salary_Englishinyourlife&id_campaign=16&id=25094"/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="landing_page">Landing Page</label>
                                        <label class="select col col-8">
                                            <select name="landing_page" id="landing_page">
                                                <option value="" selected>Select Landing Page</option>
                                                @foreach ($landing_pages as $item)
                                                    <option value="{{ $item->id }}">{{ $item->url }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-4 require_field" for="channel">Channel</label>
                                        <label class="select col col-8">
                                            <select name="channel" id="channel">
                                                <option value="" selected>Select Channel</option>
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
                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="float:left; width: 20%;">
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-ads" action="{{ route("ads-create") }}">
                {{ csrf_field() }}
                <input type="hidden" name="subcampaign_id" value="{{ $subcampaign->id }}"/>
                <input type="hidden" name="ads_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Ads</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
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