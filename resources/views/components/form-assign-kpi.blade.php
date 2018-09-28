<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-assign-kpi" action="{{ route("assign-kpi") }}" url="{{ route("save-kpi") }}">
                <input type="hidden" name="config_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Assign KPI</h3>
                </div>
                <div class="modal-body">
                    <div class="smart-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <section class="col col-sm-3">
                                <label class="label">Marketer</label>
                                <label class="select">
                                    <select id="username">
                                        @foreach($users as $user)
                                            <option value="{{$user->_id}}">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-sm-3">
                                <label class="label">Month</label>
                                <label class="select">
                                    <select id="month">
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-sm-3">
                                <label class="label">Year</label>
                                <label class="select">
                                    <select id="year">
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                        <hr style="padding: 10px">
                        <div class="row lst_days">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="assign_kpi" type="submit" class="btn btn-primary">
                        Assign
                    </button>
                    <button id="assign_close_kpi" type="submit" class="btn btn-default" data-dismiss="modal">
                        Assign & Close
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
