@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                {{--<a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"--}}
                   {{--data-toggle="modal"--}}
                   {{--data-target="#addModal"><i--}}
                            {{--class="fa fa-map-signs fa-lg"></i> Assign KPI</a>--}}
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">
                        @component('components.jarviswidget',
                                                    ['id' => 'report_kpi', 'icon' => 'fa-table', 'title' => 'Report KPI', 'dropdown' => 'true'])
                            <!-- Nav tabs -->
                                <ul class="nav nav-tabs" style="padding-bottom: 10px">
                                    <li class="active"><a data-toggle="tab" href="#maketer">By Maketer</a></li>
                                    <li><a data-toggle="tab" href="#team">By Team</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane container fade in active" id="maketer">
                                        <div class="smart-form">
                                            <section>
                                                <label class="input">
                                                    <input type="text" value="" name="maketer_name" placeholder="Select maketer">
                                                </label>
                                            </section>
                                        </div>

                                        <div class="row">
                                            <section class="col col-sm-6 col-lg-3">
                                                <label class="kpi_label">KPI Selection</label>
                                                <select name="kpi_selection" class="select2" style="width: 280px" id="kpi_selection"
                                                        data-url="">
                                                    <option value="c3b">C3B</option>
                                                    <option value="c3b_cost">C3B Cost</option>
                                                    <option value="l3_c3bg">L3/C3BG</option>
                                                </select>
                                                <i></i>
                                            </section>
                                            <section>
                                                <div id="" class="text-right" style="margin: 10px 0px; padding: 10px 7px;">
                                                    <button id="filter_maketer" class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                                        <i class="fa fa-filter"></i>
                                                        Filter
                                                    </button>
                                                </div>
                                            </section>
                                        </div>

                                        <div id="wrapper_kpi">
                                            @include('pages.table_report_kpi')
                                        </div>
                                    </div>
                                    <div class="tab-pane container fade" id="team">
                                        <div class="smart-form">
                                            <section>
                                                <label class="input">
                                                    <input type="text" value="" name="team" placeholder="Select team">
                                                </label>
                                            </section>
                                        </div>

                                        <div class="row">
                                            <section class="col col-sm-6 col-lg-3">
                                                <label class="kpi_label">KPI Selection</label>
                                                <select name="kpi_selection" class="select2" style="width: 280px" id="kpi_selection"
                                                        data-url="">
                                                    <option value="c3b">C3B</option>
                                                    <option value="c3b_cost">C3B Cost</option>
                                                    <option value="l3_c3bg">L3/C3BG</option>
                                                </select>
                                                <i></i>
                                            </section>
                                            <section>
                                                <div id="" class="text-right" style="margin: 10px 0px; padding: 10px 7px;">
                                                    <button id="filter_team" class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                                        <i class="fa fa-filter"></i>
                                                        Filter
                                                    </button>
                                                </div>
                                            </section>
                                        </div>

                                        <div id="wrapper_kpi_by_team">
                                            @include('pages.table_report_kpi_by_team')
                                        </div>
                                    </div>
                                </div>

                        @endcomponent
                    </article>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

                @include('components.form-assign-kpi', ['type' => null])
        </div>

        <!-- END MAIN CONTENT -->

    </div>
    <!-- END MAIN PANEL -->
    <input type="hidden" id="get_kpi_url" value="{{route('get-kpi')}}">
    <input type="hidden" id="kpi_by_team_url" value="{{route('kpi-by-team')}}">
    <input type="hidden" id="kpi_by_maketer_url" value="{{route('kpi-by-maketer')}}">
    <input type="hidden" id="selected_month" value="">
    <input type="hidden" id="selected_year" value="">

@endsection


@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
<script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>

<style>
    .kpi_label {
        display: block;
        margin-bottom: 6px;
        line-height: 19px;
        font-weight: bolder;
        font-size: 13px;
        color: #333;
        text-align: left;
        white-space: normal;
    }
</style>


<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        $("#table_kpi").tableHeadFixer({"left" : 5, 'z-index': 0});
        $("#table_kpi_by_team").tableHeadFixer({"left" : 5, 'z-index': 0});

        var month = moment().month() + 1;
        if(month < 10){
            month = '0' + month;
        }
        $('#selected_month').val(month);
        $('#selected_year').val(moment().year());
        initDropdown(parseInt(month) - 1);

        $('#assign_kpi, #assign_close_kpi').click(function(e){
            e.preventDefault();
            var url = $('#form-assign-kpi').attr( 'url' );

            var year = $('select#year').val();
            var month = $('select#month').val();

            var cnt = 1;
            var kpi = {}, kpi_cost = {}, kpi_l3_c3bg = {};

            var serial = 1;

            $('input#day').each(function() {
                var value = $(this).val();
                if (!value){
                    value = 0;
                }
                if (serial === 1) {
                    kpi[cnt] = parseInt(value);
                    serial++;
                } else if (serial === 2) {
                    kpi_cost[cnt] = parseFloat(value);
                    serial++;
                } else {
                    kpi_l3_c3bg[cnt] = parseFloat(value);
                    serial = 1;
                    cnt++;
                }
            });

            var data ={};
            data.kpi         = kpi;
            data.kpi_cost    = kpi_cost;
            data.kpi_l3_c3bg = kpi_l3_c3bg;
            data.month       = month;
            data.year        = year;
            data.user_id     = $('select#username').val();
            $.get(url, data, function (data) {
                month = moment().month() + 1;
                if(month < 10){
                    month = '0' + month;
                }
                initDataKPI(month);
                initDataKPIByteam(month);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        });

        $('select#month').change(function(e){
            e.preventDefault();
            var year   = $('select#year').val();
            var month   = $('select#month').val();
            var user    = $('select#username').val();

            initFormKPI(user, month, year);
        });

        $('select#year').change(function(e){
            e.preventDefault();
            var year   = $('select#year').val();
            var month   = $('select#month').val();
            var user    = $('select#username').val();

            initFormKPI(user, month, year);
        });

        $('select#username').change(function(e){
            e.preventDefault();
            var year   = $('select#year').val();
            var month   = $('select#month').val();
            var user    = $('select#username').val();

            initFormKPI(user, month, year);
        });

        $('select#kpi_selection').change(function(e){
            e.preventDefault();
            var month =  $('#selected_month').val();

            initDataKPI(month);
            initDataKPIByteam(month);
        });

        $('#addModal').on('shown.bs.modal', function () {
            var user    = $(this).attr('data-user-id');
            var month   = $('#selected_month').val();
            var year    = $('#selected_year').val();

            initFormKPI(user, month, year);
        });

        $('li#month').click(function() {
            var month_name = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            var month       = $(this).val();
            var dropdown    = $(this).closest('ul').siblings();
            dropdown.html(month_name[month-1]);

            var title       = $(this).parents('div.widget-toolbar').siblings('h2');
            title.html('Report KPI in ' + dropdown.html());

            if(month < 10){
                month = '0' + month;
            }
            $('#selected_month').val(month);

            initDataKPI(month);
            initDataKPIByteam(month);
        });

        $('a.edit_kpi').click(function() {
            var user = $(this).attr('data-user-id');
            $('#addModal').attr('data-user-id', user);
        });

        $('input[name=maketer_name]').selectize({
            delimiter: ',',
            persist: false,
            valueField: '_id',
            labelField: 'username',
            searchField: ['username'],
            options: {!! $users !!}
        });

        $('input[name=team]').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            options: {!! $teams !!}
        });

        $('#filter_maketer').click(function() {
            var month =  $('#selected_month').val();
            initDataKPI(month);
        });

        $('#filter_team').click(function() {
            var month =  $('#selected_month').val();
            initDataKPIByteam(month);
        });

        /* END BASIC */
    });

    function set_user_id(item){
        var user = $(item).attr('data-user-id');
        $('#addModal').attr('data-user-id', user);

        var year = moment().year();
        var options = "<option selected='selected'>"+ year +"</option>";
        options += "<option>"+ (year + 1) +"</option>";
        document.getElementById("year").innerHTML = options;
    }

    function initFormKPI(user, month, year){

        $('select#year').val(year);
        $('select#month').val(month);
        $('select#username').val(user);

        initLstDays(user, month, year);
    }

    function initLstDays(user ,month, year) {
        var days = new Date(year, month, 0).getDate();
        var url  = $('input#get_kpi_url').val();
        var month_name = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        // $('#assign_kpi').attr('disabled', 'disabled');
        // $('#assign_close_kpi').attr('disabled', 'disabled');

        var data ={};
        data.month      = month;
        data.year       = year;
        data.user_id    = user;

        $.get(url, data, function (data) {
            $("div.lst_days").html('');
            var i;
            var kpi_total = 0, kpi_cost_total = 0, kpi_l3_c3bg_total = 0;
            for (i = 1; i <= days; i++) {
                kpi_total += data['kpi'][i] ? parseInt(data['kpi'][i]) : 0;
                kpi_cost_total += data['kpi_cost'][i] ? parseFloat(data['kpi_cost'][i]) : 0;
                kpi_l3_c3bg_total += data['kpi_l3_c3bg'][i] ? parseFloat(data['kpi_l3_c3bg'][i]) : 0;
            }
            kpi_cost_total = Math.round(kpi_cost_total * 100 / days) / 100;
            kpi_l3_c3bg_total = Math.round(kpi_l3_c3bg_total * 100 / days) / 100;
            $("div.lst_days").append('<div class="row">' +
                '   <section class="col col-3">' +
                '       <label class="label" style="margin: 25px 0 0 8px;">Enter KPIs</label>'+
                '   </section>'+
                '   <section class="col col-2">C3B' +
                '       <input class="form-control" id="total" type="number" value="'+kpi_total+'" placeholder="C3B KPI" ' +
                '           max="" min="0" data-toggle="tooltip" title="Enter KPIs...">' +
                '   </section>'+
                '   <section class="col col-2">C3B Cost' +
                '       <input class="form-control" id="total" type="number" value="'+kpi_cost_total+'" placeholder="C3B KPI" ' +
                '           max="" min="0" step="0.01" data-toggle="tooltip" title="Enter KPIs...">' +
                '   </section>'+
                '   <section class="col col-2">L3/C3BG' +
                '       <input class="form-control" id="total" type="number" value="'+kpi_l3_c3bg_total+'" placeholder="C3B KPI" ' +
                '           max="" min="0" step="0.01" data-toggle="tooltip" title="Enter KPIs...">' +
                '   </section>' +
                '   <section class="col col-3">' +
                '       <button id="auto_assign" type="button" onclick="autoAssign()" class="btn btn-success" ' +
                '           style="margin: 18px 0 0 8px; padding: 7px">Auto-Assign' +
                '       </button>' +
                '   </section>' +
                '</div>' +
                '<div class="row" style="margin: 0 20px 15px 0; text-align: right;">' +
                '   <button type="button" onclick="assignKpi()" style="padding: 6px 12px;" class="btn btn-primary">' +
                '       Assign' +
                '   </button>' +
                '   <button type="button" onclick="assignKpi()" style="padding: 6px 12px; margin-left: 5px;" class="btn btn-default" data-dismiss="modal">' +
                '       Assign & Close' +
                '   </button>' +
                '   <button type="button" style="padding: 6px 12px; margin-left: 5px;" class="btn btn-default" data-dismiss="modal">' +
                '       Cancel' +
                '   </button>' +
                '</div>' +
                '<hr style="padding: 10px">');
            for (i = 1; i <= days; i++) {
                var kpi_val = data['kpi'][i] ? data['kpi'][i] : 0;
                var kpi_cost_val = data['kpi_cost'][i] ? data['kpi_cost'][i] : 0;
                var kpi_l3_c3bg_val = data['kpi_l3_c3bg'][i] ? data['kpi_l3_c3bg'][i] : 0;
                var day = i < 10 ? '0' + i : i;
                var item =
                    '<div class="row">' +
                    '   <section class="col col-3">' +
                    '       <label class="label" style="margin: 8px;">'+ day + " " + month_name[month - 1] +'</label>'+
                    '   </section>'+
                    '   <section class="col col-2">' +
                    '       <input class="form-control" id="day" type="number" value="'+ kpi_val +'"' +
                    '           placeholder="" max="" min="0" data-toggle="tooltip" title="Enter KPIs...">' +
                    '   </section>'+
                    '   <section class="col col-2">' +
                    '       <input class="form-control" id="day" type="number" value="'+ kpi_cost_val +'"' +
                    '           placeholder="" max="" min="0" step="0.01" data-toggle="tooltip" title="Enter KPIs...">' +
                    '   </section>'+
                    '   <section class="col col-2">' +
                    '       <input class="form-control" id="day" type="number" value="'+ kpi_l3_c3bg_val +'"' +
                    '           placeholder="" max="" min="0" step="0.01" data-toggle="tooltip" title="Enter KPIs...">' +
                    '   </section>' +
                    '</div>';

                $("div.lst_days").append(item);
                // $('#assign_kpi').attr('disabled', false);
                // $('#assign_close_kpi').attr('disabled', false);
            }
        }).fail(
            function (err) {
                alert('Cannot connect to server. Please try again later.');
            });
    }

    function assignKpi() {
        $('#assign_kpi').click();
    }

    function autoAssign() {
        var kpi = 0, mod = 0, kpi_cost = 0, kpi_l3_c3 = 0;
        var year   = $('select#year').val();
        var month   = $('select#month').val();
        var days = new Date(year, month, 0).getDate();

        var serial = 1;
        $('input#total').each(function () {
            var value = $(this).val();
            if (serial === 1) {
                mod = parseInt(value) % days;
                if(mod !== 0) {
                    value = value - mod;
                }
                kpi = value / days;
                serial++;
            } else if (serial === 2) {
                kpi_cost = parseFloat(value);
                $(this).val(kpi_cost);
                serial++;
            } else {
                kpi_l3_c3 = parseFloat(value);
                $(this).val(kpi_l3_c3);
                serial = 1;
            }
        });

        $('input#day').each(function () {
            if (serial === 1) {
                if (mod > 0) {
                    $(this).val(kpi + 1);
                    mod--;
                } else {
                    $(this).val(kpi);
                }
                serial++;
            } else if (serial === 2) {
                $(this).val(kpi_cost);
                serial++;
            } else {
                $(this).val(kpi_l3_c3);
                serial = 1;
            }
        });
    }

    function initDropdown(month){
        var month_name = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        var dropdown = $('button#dropdown');
        dropdown.html(month_name[month]);
        $('h2#report_kpi').html('Report KPI in <span class="yellow">' + dropdown.html()+ '</span>');
        $('button#dropdown').click();
    }
    
    function initDataKPI(month) {
        var url = $('#kpi_by_maketer_url').val();
        var maketer = $('input[name=maketer_name]').val();
        var kpi_selection  = $('select#kpi_selection').val();

        $('.loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                month   : month,
                maketer : maketer,
                kpi_selection : kpi_selection
            }
        }).done(function (response) {
            $('#wrapper_kpi').html(response);
            $("#table_kpi").tableHeadFixer({"left" : 5, 'z-index': 0});
            initDropdown(parseInt(month) - 1);
            $('button#dropdown').click();
        });
        setTimeout(function(){
            $('.loading').hide();
        }, 2000);
    }

    function initDataKPIByteam(month) {
        var url = $('#kpi_by_team_url').val();
        var team = $('input[name=team]').val();
        var kpi_selection  = $('select#kpi_selection').val();

        $('.loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                month   : month,
                team    : team,
                kpi_selection : kpi_selection
            }
        }).done(function (response) {
            $('#wrapper_kpi_by_team').html(response);
            $("#table_kpi_by_team").tableHeadFixer({"left" : 5, 'z-index': 0});
            initDropdown(parseInt(month) - 1);
            $('button#dropdown').click();
        });
        setTimeout(function(){
            $('.loading').hide();
        }, 2000);
    }

</script>
@stop
