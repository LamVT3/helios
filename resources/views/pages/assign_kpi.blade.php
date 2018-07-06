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
                                        <div id="wrapper_kpi">
                                            @include('pages.table_report_kpi')
                                        </div>
                                    </div>
                                    <div class="tab-pane container fade" id="team">
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


@endsection


@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>


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
        initDropdown(parseInt(month) - 1);

        $('#assign-kpi').click(function(e){
            e.preventDefault();
            var url = $('#form-assign-kpi').attr( 'url' );

            var year = moment().year();
            var month = $('select#month').val();

            var cnt = 1;
            var kpi = {};

            $('input#day').each(function() {
                var value = $(this).val();
                if (!value){
                    value = 0;
                }
                kpi[cnt] = value;
                cnt++;
            });

            var data ={};
            data.kpi        = kpi;
            data.month      = month;
            data.year       = year;
            data.user_id   = $('select#username').val();

            $.get(url, data, function (data) {
                initDataKPI(month);
                initDataKPIByteam(month);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        });

        $('select#month').change(function(e){
            e.preventDefault();
            var month   = $('select#month').val();
            var user    = $('select#username').val();

            initFormKPI(user, month);
        });

        $('select#username').change(function(e){
            e.preventDefault();
            var month   = $('select#month').val();
            var user    = $('select#username').val();

            initFormKPI(user, month);
        });

        $('#addModal').on('shown.bs.modal', function () {
            var user    = $(this).attr('data-user-id');
            var month   = $('#selected_month').val();

            initFormKPI(user, month);
        })

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
            initDataKPIByteam(month)
        });

        $('a.edit_kpi').click(function() {
            var user = $(this).attr('data-user-id');
            $('#addModal').attr('data-user-id', user);
        });

        /* END BASIC */
    })

    function set_user_id(item){
        var user = $(item).attr('data-user-id');
        $('#addModal').attr('data-user-id', user);
    }

    function initFormKPI(user, month){

        $('select#month').val(month);
        $('select#username').val(user);

        initLstDays(user, month);
    }

    function initLstDays(user ,month) {
        var year = moment().year();
        var days = new Date(year, month, 0).getDate();
        var url  = $('input#get_kpi_url').val();
        var month_name = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $('#assign-kpi').attr('disabled', 'disabled');

        var data ={};
        data.month      = month;
        data.year       = year;
        data.user_id    = user;

        $.get(url, data, function (data) {
            $("div.lst_days").html('');
            var i;
            for (i = 1; i <= days; i++) {
                var value = data[i] ? data[i] : 0;
                var day = i < 10 ? '0' + i : i;
                var item =
                    '<section class="col col-2">' +
                    day + month_name[month - 1] +
                    '    <input class="form-control" id="day" type="number" value="'+ value +'"' +
                    '           placeholder="" max="" min="1" data-toggle="tooltip" title="Enter KPIs...">' +
                    '</section>';

                $("div.lst_days").append(item);
                $('#assign-kpi').attr('disabled', false);
            }
        }).fail(
            function (err) {
                alert('Cannot connect to server. Please try again later.');
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

        $('.loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                month : month
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

        $('.loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                month : month
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
