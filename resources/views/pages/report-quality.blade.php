@extends('layouts.master')

@section('content')
<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content">
{{-- --}}
        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'currency' => true])
            @component('components.currency')
            @endcomponent
        @endcomponent

        <div class="tab-v1">
            <ul id="tabs" class="nav nav-tabs">
                <li class="active"><a href="#report" data-toggle="tab"><strong>Report</strong></a></li>
                <li {{--class="active"--}}><a href="#monthly" data-toggle="tab"><strong>Monthly Report</strong></a></li>
                <li {{--class="active"--}}><a href="#year" data-toggle="tab"><strong>Latest Months Report</strong></a></li>
                <li {{--class="active"--}}><a href="#statistic" data-toggle="tab"><strong>Statistic Report</strong></a></li>
            </ul>
            <div class="tab-content mb30" style="margin-top: 10px">
                <div id="report" class="tab-pane active">
                    <!-- widget grid -->
                    <section id="widget-grid" class="">
                        <!-- row -->
                        <div class="row">
                            <article class="col-sm-12 col-md-12">
                                @component('components.jarviswidget',
                                ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report'])
                                <div class="widget-body">

                                    <form id="search-form-report" class="smart-form" action="#"
                                          url="{!! route('report.filter') !!}">
                                        <div class="row">
                                            <div id="reportrange" class="pull-left"
                                                 style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                <span class="registered_date"></span> <b class="caret"></b>
                                            </div>
                                        </div>
                                        {{--<fieldset>--}}
                                            {{--<legend>Filter--}}
                                                {{--<a id="filter" href="javascript:void(0)"><i class="fa fa-angle-up fa-lg"></i></a>--}}
                                            {{--</legend>--}}
                                            <div class="row" id="filter">
                                                <section class="col col-2">
                                                    <label class="label">Source</label>
                                                    <select name="source_id" class="select2" style="width: 280px" id="source_id" tabindex="1" autofocus
                                                            data-url="{!! route('ajax-getFilterSource') !!}">
                                                        <option value="">All</option>
                                                        @foreach($sources as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                                <section class="col col-2">
                                                    <label class="label">Team</label>
                                                    <select name="team_id" class="select2" id="team_id" style="width: 280px" tabindex="2"
                                                            data-url="{!! route('ajax-getFilterTeam') !!}">
                                                        <option value="">All</option>
                                                        @foreach($teams as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                                <section class="col col-2">
                                                    <label class="label">Marketer</label>
                                                    <select name="marketer_id" id="marketer_id" class="select2" style="width: 280px"
                                                            data-url="{!! route('ajax-getFilterMaketer') !!}" tabindex="3">
                                                        <option value="">All</option>
                                                        @foreach($marketers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->username }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                                <section class="col col-2">
                                                    <label class="label">Campaign</label>
                                                    <select name="campaign_id" id="campaign_id" class="select2" style="width: 280px" tabindex="4"
                                                            data-url="{!! route('ajax-getFilterCampaign') !!}">
                                                        <option value="">All</option>
                                                        @foreach($campaigns as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                                <section class="col col-2">
                                                    <label class="label">Sub Campaign</label>
                                                    <select name="subcampaign_id" id="subcampaign_id" class="select2" style="width: 280px"
                                                            data-url="{!! route('ajax-getFilterSubCampaign') !!}">
                                                        <option value="">All</option>
                                                        @foreach($subcampaigns as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-4">
                                                    <label class="label">Landing Page</label>
                                                    <select name="landing_page" id="landing_page" class="select2" style="width: 280px"
                                                            data-url="">
                                                        <option value="">All</option>
                                                        @foreach($landing_page as $item)
                                                            <option value="{{ $item->id }}">{{ $item->url }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </section>
                                                <section class="col col-2">
                                                    <label class="label">Mode</label>
                                                    <select name="mode" id="mode" class="select2" style="width: 280px"
                                                            data-url="">
                                                        <option value="TOA" selected>TOA</option>
                                                        <option value="TOT">TOT</option>
                                                    </select>
                                                    <i></i>
                                                </section>
                                            </div>
                                            <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                                    <i class="fa fa-filter"></i>
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                        {{--</fieldset>--}}
                                    </form>
                                    {{--<div style="position: relative">
                                        <form action="{{ route('report.export')}}" enctype="multipart/form-data">
                                            <input type="hidden" name="source_id">
                                            <input type="hidden" name="marketer_id">
                                            <input type="hidden" name="campaign_id">
                                            <input type="hidden" name="team_id">
                                            <input type="hidden" name="registered_date">
                                            <div style="position: absolute; right: 75px; bottom: 0px;">
                                                <button class="btn btn-success" type="submit"
                                                        style="background-color: #3276b1;border-color: #2c699d;">Export Excel
                                                </button>
                                            </div>
                                        </form>
                                    </div>--}}
                                    <div class="loading" style="display: none">
                                        <div class="col-md-12 text-center">
                                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="wrapper_report">
                                        @include('pages.table_report-quality')
                                    </div>
                                </div>
                                @endcomponent

                            </article>
                        </div>
                        <!-- end row -->
                    </section>
                    <!-- end widget grid -->
                </div>

                <div class="tab-pane {{--active--}}" id="monthly">
                    <section id="widget-grid">
                        <div class="row">
                            <article class="col-sm-12 col-md-12">
                            @component('components.jarviswidget',
                            ['id' => 'monthly_chart', 'icon' => 'fa-table', 'title' => "Report month " , 'dropdown' => "true"])
                                <div class="loading" style="display: none">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                    </div>
                                </div>
                                <div id="monthly_report"></div>
                            @endcomponent
                            </article>
                        </div>
                    </section>
                </div>

                <div class="tab-pane" id="year">
                    <section id="widget-grid">
                        <div class="row">
                            <article class="col-sm-12 col-md-12">
                                @component('components.jarviswidget',
                                ['id' => 'year_chart', 'icon' => 'fa-table', 'title' => "Report year " , 'dropdownY' => "true"])
                                    <div class="loading" style="display: none">
                                        <div class="col-md-12 text-center">
                                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                        </div>
                                    </div>
                                    <div id="year_report"></div>
                                @endcomponent
                            </article>
                        </div>
                    </section>
                </div>

                <div class="tab-pane" id="statistic">
                    <section id="widget-grid">
                        <div class="row">
                            <article class="col-sm-12 col-md-12">
                                @component('components.jarviswidget',
                                ['id' => 'statistic_chart', 'icon' => 'fa-table', 'title' => "Report year ", 'dropdownY' => "true"])
                                    <div class="loading" style="display: none">
                                        <div class="col-md-12 text-center">
                                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                        </div>
                                    </div>
                                    <div id="statistic_report"></div>
                                @endcomponent
                            </article>
                        </div>
                    </section>
                </div>

            </div>
        </div>

    </div>
    <!-- END MAIN CONTENT -->

    <div style="position: relative">
        <form id="export-report" action="{{ route('report.export-monthly')}}" enctype="multipart/form-data">
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="startRange" id="startRange">
            <input type="hidden" name="endRange" id="endRange">
        </form>
    </div>

</div>
<input type="hidden" name="page_size" value="{{$page_size}}">
<!-- END MAIN PANEL -->

@endsection

@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/reports/report.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/custom-radio.css') }}">

<script type="text/javascript">
    var m = new Date().getMonth() + 1;
    var y = new Date().getFullYear();

    get_report_monthly(m, new Date(), new Date());

    function get_report_monthly(month, startRange, endRange) {

       $.get("{{ route('report.get-report-monthly') }}", {month: month, startRange: startRange, endRange: endRange}, function (data) {
            document.getElementById("monthly_report").innerHTML = data;
        }).fail( function (e) {
            console.log(e);
            alert('Cannot connect to server. Please try again later11.');
        }).complete(function () {
           var year = startRange.getFullYear();
           // console.log("year script = " + year);
           rangedate_span(startRange, endRange);

           $('#rangedate').daterangepicker({
               startRange: startRange.getDate(),
               endRange: endRange.getDate(),
               opens: 'right',
               minDate: new Date(year, month-1, 1),
               maxDate: new Date(year, month, 0),
           }, function(startRange, endRange){
               let start = new Date(startRange);
               let end = new Date(endRange);
               rangedate_span(start, end);
               get_report_monthly(month, start, end);
           });
        });

        $( "#monthly" ).click();
    }

    function rangedate_span(startRange, endRange) {
        /*console.log("start range = " + startRange);
        console.log("end range = " + endRange);*/
        $('#rangedate span').html(startRange.getDate() + '-' + endRange.getDate());
    }

    get_report_year(y, m, 12);

    function get_report_year(year, month, noLastMonth) {
        $.get("{{ route('report.get-report-year') }}", {year: year, month: month, noLastMonth: noLastMonth}, function (data) {
            document.getElementById("year_report").innerHTML = data;
        }).fail( function (e) {
            console.log(e);
            alert('Cannot connect to server. Please try again later22.');
        });
    }

    get_report_statistic(y, m, 12);

    function get_report_statistic(year, month, noLastMonth) {
        $.get("{{ route('report.get-report-statistic') }}", {year: year, month: month, noLastMonth: noLastMonth}, function (data) {
            document.getElementById("statistic_report").innerHTML = data;
            $('#statistic_chart_year').val(year);
            $('#statistic_chart_month').val(month);
            $('#statistic_chart_noMonth').val(noLastMonth);

            initStatisticChart();
        }).fail( function (e) {
            console.log(e);
            alert('Cannot connect to server. Please try again later33.');
        });
    }

    $(document).ready(function () {
        if(m != 12) {
            $('h2#year_chart').html('Report year <span class="yellow">' + (y-1) + ' - ' + y + '</span>');
            $('h2#statistic_chart').html('Report year <span class="yellow">' + (y-1) + ' - ' + y + '</span>');
        } else {
            $('h2#year_chart').html('Report year <span class="yellow">' + y + '</span>');
            $('h2#statistic_chart').html('Report year <span class="yellow">' + y + '</span>');
        }

        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {
            var elementDropdown = document.getElementById("dropdown");
            elementDropdown.setAttribute("aria-expanded", "false");
            elementDropdown.parentElement.classList.remove("open");

            var elementDropdownY = document.getElementById("dropdownY");
            elementDropdownY.setAttribute("aria-expanded", "false");
            elementDropdownY.parentElement.classList.remove("open");
        };

    });

    $('#monthly_report').on('click', 'button#confirm_export', function (e) {
        e.preventDefault();
        var monthArr = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        var month = document.getElementById('dropdown').textContent;
        var rangeDate = $("#rangedate").find("span").text();
        let dateArr = rangeDate.split("-");
        month = monthArr.indexOf(month) + 1;

        $('input#month').val(month);
        $('input#startRange').val(new Date(2018, month, dateArr[0]));
        $('input#endRange').val(new Date(2018, month, dateArr[1]));

        $('#export-report').submit();

    });

</script>

@include('components.script-jarviswidget')
@include('components.script-statistic-chart')

@stop
