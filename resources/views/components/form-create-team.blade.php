<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-team" action="{{ route("team-create") }}">
                <input type="hidden" name="team_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Create Team</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div id="form-team-alert"></div>
                        <section>
                            <label class="label require_field" for="name">Team Name</label>
                            <label class="input">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Team name..."/>
                            </label>
                        </section>
                        <section>
                            <label class="label require_field" for="description">Team Description</label>
                            <label class="textarea">
                                <textarea name="description" class="form-control" id="description" rows="3"
                                          placeholder="Team Description..."></textarea>
                            </label>
                        </section>
                        <section>
                            <label class="label">Team members</label>
                            <label class="input">
                                <input type="text" value="{{ $members }}" name="members" placeholder="Select members">
                            </label>

                        </section>
                        <section>
                            <label class="label">Sources</label>
                            <label class="input">
                                <input type="text" value="{{ $sources }}" name="sources" placeholder="Select sources">
                            </label>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Create Team
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->
</div>