@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                        ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report'])
                            <div class="widget-body">

                                <form id="search-form-hour-report" class="smart-form" method="post" action="{!! route('hour-report.filter') !!}">
                                    {{csrf_field()}}
                                    <div class="row" id="filter">
                                        <section class="col col-2">
                                            <label class="label">Source</label>
                                            <select name="source_id" class="select2" style="width: 280px" id="source_id"
                                                    tabindex="1" autofocus
                                                    data-url="{!! route('ajax-getFilterSource') !!}">
                                                <option value="">All</option>
                                                @foreach($sources as $item)
                                                    <option value="{{ $item->id }}" @if( $item->id == @$data_where['source_id']) Selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                        <section class="col col-2">
                                            <label class="label">Team</label>
                                            <select name="team_id" class="select2" id="team_id" style="width: 280px"
                                                    tabindex="2"
                                                    data-url="{!! route('ajax-getFilterTeam') !!}">
                                                <option value="">All</option>
                                                @foreach($teams as $item)
                                                    <option value="{{ $item->id }}" @if( $item->id == @$data_where['team_id']) Selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                        <section class="col col-2">
                                            <label class="label">Marketer</label>
                                            <select name="marketer_id" id="marketer_id" class="select2"
                                                    style="width: 280px"
                                                    data-url="{!! route('ajax-getFilterMaketer') !!}" tabindex="3">
                                                <option value="">All</option>
                                                @foreach($marketers as $item)
                                                    <option value="{{ $item->id }}" @if( $item->id == @$data_where['marketer_id']) Selected @endif>{{ $item->username }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                        <section class="col col-2">
                                            <label class="label">Campaign</label>
                                            <select name="campaign_id" id="campaign_id" class="select2"
                                                    style="width: 280px" tabindex="4"
                                                    data-url="{!! route('ajax-getFilterCampaign') !!}">
                                                <option value="">All</option>
                                                @foreach($campaigns as $item)
                                                    <option value="{{ $item->id }}" @if( $item->id == @$data_where['campaign_id']) Selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                        <section class="col col-2">
                                            <label class="label">Sub Campaign</label>
                                            <select name="subcampaign_id" id="subcampaign_id" class="select2"
                                                    style="width: 280px"
                                                    data-url="">
                                                <option value="">All</option>
                                                @foreach($subcampaigns as $item)
                                                    <option value="{{ $item->id }}" @if( $item->id == @$data_where['subcampaign_id']) Selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-2">
                                            <label class="label">Choose date</label>
                                            <input type="date" name="date_time" class="form-control" value="{{$date_time}}">
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary btn-sm" type="submit"
                                                    style="margin-right: 15px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loading" style="display: none">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                             style="width: 2%;"/>
                                    </div>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <article class="col-sm-12 col-md-12">
                                            <table class="table table-bordered table-hover"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>C3A</th>
                                                    <th>C3B</th>
                                                    <th>C3BG</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @for($i = 0; $i < 24; $i++)
                                                    <tr>
                                                        <td>{{$i}}h</td>
                                                        <td style="color:{{($table['c3'][$i]) >= ($table['c3_week'][$i]) ? 'green' : 'red'}}">{{$table['c3'][$i]}}</td>
                                                        <td style="color:{{$table['c3b'][$i] >= $table['c3b_week'][$i] ? 'green' : 'red'}}">{{$table['c3b'][$i]}}</td>
                                                        <td style="color:{{$table['c3bg'][$i] >= $table['c3bg_week'][$i] ? 'green' : 'red'}}">{{$table['c3bg'][$i]}}</td>
                                                    </tr>
                                                @endfor
                                                    <tr>
                                                        <th>Total</th>
                                                        <th>{{array_sum($table['c3'])}}</th>
                                                        <th>{{array_sum($table['c3b'])}}</th>
                                                        <th>{{array_sum($table['c3bg'])}}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </article>
                                    </div>

                                    <div class="col-sm-8">
                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3bg', 'icon' => 'fa-line-chart', 'title' => "C3BG ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3bg_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>
                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3b', 'icon' => 'fa-line-chart', 'title' => "C3B ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3b_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3', 'icon' => 'fa-line-chart', 'title' => "C3", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <article class="col-sm-12 col-md-12">
                                            <table class="table table-bordered table-hover"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>C3</th>
                                                    <th>C3B</th>
                                                    <th>C3BG</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @for($i = 0; $i <= 23; $i++)
                                                    <tr>
                                                        <td>{{$i}}h</td>
                                                        <td>{{$table_accumulated['c3'][$i]}}</td>
                                                        <td>{{$table_accumulated['c3b'][$i]}}</td>
                                                        <td>{{$table_accumulated['c3bg'][$i]}}</td>
                                                    </tr>
                                                @endfor
                                                </tbody>
                                            </table>
                                        </article>
                                    </div>

                                    <div class="col-sm-8">

                                        <article class="col-sm-12 col-md-12">
                                            <div class="loading" style="display: none">
                                                <div class="col-md-12 text-center">
                                                    <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                                         style="width: 2%;"/>
                                                </div>
                                            </div>
                                            <br>
                                        @component('components.jarviswidget',
                                        ['id' => 'c3bg_accumulated', 'icon' => 'fa-line-chart', 'title' => "C3BG ", 'dropdown' => 'true'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    @component('components.hour_chart', ['id' => 'c3bg_chart_accumulated', 'chk' => 'hourc3bg_chk'])
                                                    @endcomponent
                                                </div>
                                            @endcomponent
                                        </article>
                                        <article class="col-sm-12 col-md-12">
                                            <div class="loading" style="display: none">
                                                <div class="col-md-12 text-center">
                                                    <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                                         style="width: 2%;"/>
                                                </div>
                                            </div>
                                            <br>
                                        @component('components.jarviswidget',
                                        ['id' => 'c3b_accumulated', 'icon' => 'fa-line-chart', 'title' => "C3B ", 'dropdown' => 'true'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    @component('components.hour_chart', ['id' => 'c3b_chart_accumulated', 'chk' => 'hourc3b_chk'])
                                                    @endcomponent
                                                </div>
                                            @endcomponent
                                        </article>
                                        <article class="col-sm-12 col-md-12">
                                            <div class="loading" style="display: none">
                                                <div class="col-md-12 text-center">
                                                    <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                                         style="width: 2%;"/>
                                                </div>
                                            </div>
                                            <br>
                                        @component('components.jarviswidget',
                                        ['id' => 'c3_accumulated', 'icon' => 'fa-line-chart', 'title' => "C3 ", 'dropdown' => 'true'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    @component('components.hour_chart', ['id' => 'c3_chart_accumulated', 'chk' => 'hourc3_chk'])
                                                    @endcomponent
                                                </div>
                                            @endcomponent
                                        </article>

                                    </div>

                                </div>

                            </div>
                        @endcomponent

                    </article>

                </div>
                <!-- end row -->

            </section>
            <!-- end widget grid -->

        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <!-- END MAIN PANEL -->
    <input type="hidden" name="c3_month">
    <input type="hidden" name="c3b_month">
    <input type="hidden" name="c3bg_month">

@endsection

@section('script')
    <!-- PAGE RELATED PLUGIN(S) -->
    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
    <script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script src="{{ asset('js/reports/hour-report.js') }}"></script>

    <script type="text/javascript">

        var arr_color = ["#800000", "#6A5ACD", "#808080", "#7CFC00", "#FF8C00", "#1E90FF", "#000", "#008000",
                         "#FFCCCC", "#999933", "#FF6600", "#9999FF", "#FF66FF", "#000088", "#000022", "#99FF99",
                         "#33FF66", "#FFCC33", "#CCCC00", "#CC0099", "#990099", "#FF3333", "#009999", "#006666"
        ];
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {
            pageSetUp();
            initC3();
            initC3B();
            initC3BG();
            initC3Accumulated();
            initC3BAccumulated();
            initC3BGAccumulated();
        });

        function initC3() {
            var item = $("#c3_chart");
            var data = [
                {data : {{ $chart["c3_week"] }},label : "C3 Week", color: "#FF8C00"},
                {data: {{$chart['c3']}}, label: "C3", color: "#7CFC00"},
            ];

            initChart(item, data);
            item.UseC3Tooltip();
        }

        function initC3B() {
            var item = $("#c3b_chart");
            var data = [
                {data : {{ $chart["c3b_week"] }},label : "C3B Week", color: "#FF8C00"},
                {data: {{$chart['c3b']}}, label: "C3B", color: "#1E90FF"},
            ];

            initChart(item, data, ["#1E90FF"]);
            $("#c3b_chart").UseC3BTooltip();
        }

        function initC3BG() {
            var item = $("#c3bg_chart");
            var data = [
                {data : {{ $chart["c3bg_week"] }},label : "C3BG Week", color: "#FF8C00"},
                {data: {{$chart['c3bg']}}, label: "C3BG", color: "#6A5ACD"},
            ];

            initChart(item, data);
            $("#c3bg_chart").UseC3BGTooltip();
        }

        function initC3Accumulated() {
            var item = $("#c3_chart_accumulated");
            var data = [
                    {data: {{ $chart_c3[$current_hour] }}, label: "{{$current_hour}}h"},
            ];
            initChartA(item, data, [arr_color[{{$current_hour}}]]);
            item.UseTooltip();
        }

        function initC3BAccumulated() {
            var item = $("#c3b_chart_accumulated");
            var data = [
                {data: {{ $chart_c3b[$current_hour] }}, label: "{{$current_hour}}h"},
            ];

            initChartA(item, data, [arr_color[{{$current_hour}}]]);
            item.UseTooltip();
        }

        function initC3BGAccumulated() {
            var item = $("#c3bg_chart_accumulated");
            var data = [
                {data: {{ $chart_c3bg[$current_hour] }}, label: "{{$current_hour}}h"},
            ];

            initChartA(item, data, [arr_color[{{$current_hour}}]]);
            item.UseTooltip();
        }

    </script>
    <script type="text/javascript">
        var __arr_month = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];

        $( document ).ready(function() {
            var d = new Date();
            var current_month = parseInt(d.getMonth() + 1);
            var current_hour = {{$current_hour}};
            var dropdown = $('button#dropdown');
            dropdown.html(__arr_month[current_month-1]);

            $('input[name="c3_month"]').val(current_month);
            $('input[name="c3b_month"]').val(current_month);
            $('input[name="c3bg_month"]').val(current_month);

            $('h2#c3_accumulated').html('C3 in ' + dropdown.html());
            $('h2#c3b_accumulated').html('C3B in ' + dropdown.html());
            $('h2#c3bg_accumulated').html('C3BG in ' + dropdown.html());

            var dropdownLst = document.getElementsByClassName('dropdown-toggle');
            for (var i = 0; i < dropdownLst.length; i++) {
                dropdownLst[i].parentElement.parentElement.parentElement.classList.add('sticky');
            }

            jQuery.each($('#hourc3_chk').find('input[type=checkbox]'), function (index, checkbox) {
                $label = $(checkbox).val();
                if ($label == current_hour) {
                    $(this).prop('checked',true);
                }
            });
            jQuery.each($('#hourc3b_chk').find('input[type=checkbox]'), function (index, checkbox) {
                $label = $(checkbox).val();
                if ($label == current_hour) {
                    $(this).prop('checked',true);
                }
            });
            jQuery.each($('#hourc3bg_chk').find('input[type=checkbox]'), function (index, checkbox) {
                $label = $(checkbox).val();
                if ($label == current_hour) {
                    $(this).prop('checked',true);
                }
            });

            $('#c3_accumulated').click();

            function get_accumulated_chart(element, url, month) {

                if(month < 10){
                    month = "0" + month.toString();
                }
                else {
                    month = month.toString();
                }

                element.parent().parent().parent().parent().find('.loading').css("display", "block");
                $.get(url, {month: month}, function (rs) {
                    var data = [
                        {data: jQuery.parseJSON(rs[current_hour] ), label: current_hour + "h"},
                        ];
                    initChartA(element, data, arr_color);
                    element.parent().parent().parent().parent().find('.loading').css("display", "none");
                    element.UseTooltip();
                }).fail(
                    function (err) {
                        alert('Cannot connect to server. Please try again later.');
                    });
            }

            $('li#month').click(function() {
                var month       = $(this).val();
                var dropdown    = $(this).closest('ul').siblings();
                dropdown.html(__arr_month[month - 1]);

                var title       = $(this).parents('div.widget-toolbar').siblings('h2');
                var title_id    = title.attr('id');
                if(title_id == 'c3_accumulated'){
                    title.html('C3 in ' + dropdown.html());
                    $('input[name="c3_month"]').val(month);
                    get_accumulated_chart($("#c3_chart_accumulated"), "{{route('ajax-getHourC3Chart')}}", month);
                } else if (title_id == 'c3b_accumulated'){
                    title.html('C3B in ' + dropdown.html());
                    $('input[name="c3b_month"]').val(month);
                    get_accumulated_chart($("#c3b_chart_accumulated"), "{{route('ajax-getHourC3BChart')}}", month);
                } else if (title_id == 'c3bg_accumulated'){
                    title.html('C3BG in ' + dropdown.html());
                    $('input[name="c3bg_month"]').val(month);
                    get_accumulated_chart($("#c3bg_chart_accumulated"), "{{route('ajax-getHourC3BGChart')}}", month);
                }
            });

            $('#hourc3_chk input[type=checkbox]').change(function () {
                var month = $('input[name="c3_month"]').val();
                get_chart($('#hourc3_chk'), $("#c3_chart_accumulated"), "{{route('ajax-getHourC3Chart')}}", month);
            });
            $('#hourc3b_chk input[type=checkbox]').change(function () {
                var month = $('input[name="c3b_month"]').val();
                get_chart($('#hourc3b_chk'), $("#c3b_chart_accumulated"), "{{route('ajax-getHourC3BChart')}}", month);
            });
            $('#hourc3bg_chk input[type=checkbox]').change(function () {
                var month = $('input[name="c3bg_month"]').val();
                get_chart($('#hourc3bg_chk'), $("#c3bg_chart_accumulated"), "{{route('ajax-getHourC3BGChart')}}", month);
            });

        });

    </script>

@endsection