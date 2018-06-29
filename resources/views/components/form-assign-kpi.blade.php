<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-assign-kpi" action="#" url="{{ route("assign-kpi") }}">
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
                                <label class="label">Month</label>
                                <label class="select">
                                    <select id="month">
                                        <option value="01">Jan</option>
                                        <option value="02">Feb</option>
                                        <option value="03">Mar</option>
                                        <option value="04">Apr</option>
                                        <option value="05">May</option>
                                        <option value="06">Jun</option>
                                        <option value="07">Jul</option>
                                        <option value="08">Aug</option>
                                        <option value="09">Sep</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                        <hr>
                        <div class="row lst_days">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="assign-kpi" type="submit" class="btn btn-primary" data-dismiss="modal">
                        Assign
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
