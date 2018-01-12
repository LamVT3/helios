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

                    {{--@unless(auth()->user()->sources)
                        <p class="text-warning">This user can not create a campaign because it hasn't been assigned to a team.</p>
                    @else--}}
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-campaign-alert"></div>
                        <fieldset>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="source">Source</label>
                                    <label class="select col col-9">
                                        <select name="source" id="source">
                                            @foreach(auth()->user()->sources as $item)
                                                <option value="{{ $item['source_id'] or '' }}" {{ $campaign->source_id == $item['source_id'] ? "selected" : "" }}>{{ $item['source_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section>
                                    <label class="label col col-3" for="team">Team</label>
                                    <label class="select col col-9">
                                        <select name="team" id="team">
                                            @foreach(current(auth()->user()->sources)['teams'] as $item)
                                                <option value="{{ $item['team_id'] or '' }}" {{ $campaign->team_id == $item['team_id'] ? "selected" : "" }}>{{ $item['team_name'] }}</option>
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
                                        <label class="label col col-3" for="campaign_name">Campaign Name</label>
                                        <label class="input col col-9">
                                            <input type="text" name="campaign_name" id="campaign_name"
                                                   class="form-control"
                                                   placeholder="Enter a campaign name..."/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="medium">Medium</label>
                                        <label class="input col col-9">
                                            <input type="text" name="medium" id="medium" class="form-control"
                                                   placeholder="Enter a medium..."/>
                                        </label>
                                    </section>
                                </div>
                            </div>
                            <div id="old-campaign">
                                <div class="row">
                                    <section>
                                        <label class="label col col-3" for="campaign">Use existing</label>
                                        <label class="col col-9">
                                            <input type="text" name="campaign" id="campaign" class=""
                                                   value="{{ $campaign->id }}"
                                                   placeholder="Choose a campaign..."/>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <strong class="text-right col col-3">Medium</strong>
                                    <div class="col col-9 medium">{{ $campaign->medium }}
                                    </div>
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
                                        <label class="label col col-3" for="subcampaign_name">Subcampaign Name</label>
                                        <label class="input col col-9">
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
                                        <label class="label col col-3" for="subcampaign">Use existing</label>
                                        <label class="col col-9">
                                            <input type="text" name="subcampaign" id="subcampaign" class=""
                                                   value="{{ $subcampaign->id }}"
                                                   placeholder="Choose a subcampaign..."/>
                                        </label>
                                    </section>
                                </div>
                                {{--<div class="row">
                                    <strong class="text-right col col-3">Description</strong>
                                    <div class="col col-9 subc-desc">
                                    </div>
                                </div>--}}
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
                                        <label class="label col col-3" for="ad_name">Ad Name</label>
                                        <label class="input col col-9">
                                            <input type="text" name="ad_name" id="ad_name" class="form-control"
                                                   autofocus
                                                   placeholder="Enter an ad name..."/>
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
                    {{--@endunless--}}
                </div>
                <div class="modal-footer">
                    @if(auth()->user()->sources)
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