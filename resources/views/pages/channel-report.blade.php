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

                                <form id="search-form-channel-report" class="smart-form" action="#" url="{!! route('channel-report.filter') !!}">
                                    <div class="row" id="filter">
                                        <section class="col col-2">
                                            <label class="label">Source</label>
                                            <select name="source_id" class="select2" style="width: 280px" id="source_id"
                                                    tabindex="1" autofocus
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
                                            <select name="team_id" class="select2" id="team_id" style="width: 280px"
                                                    tabindex="2"
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
                                            <select name="marketer_id" id="marketer_id" class="select2"
                                                    style="width: 280px"
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
                                            <select name="campaign_id" id="campaign_id" class="select2"
                                                    style="width: 280px" tabindex="4"
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
                                            <select name="subcampaign_id" id="subcampaign_id" class="select2"
                                                    style="width: 280px"
                                                    data-url="">
                                                <option value="">All</option>
                                                @foreach($subcampaigns as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div id="reportrange" class="pull-left"
                                             style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                            <span class="registered_date"></span> <b class="caret"></b>
                                        </div>
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

                                <div class="row" id="wrapper_report">
                                    <div class="col-sm-12">
                                        <article class="col-sm-12 col-md-12">
                                            <table class="table table-bordered table-hover"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>C3</th>
                                                    <th>C3B</th>
                                                    <th>C3BG</th>
                                                    <th>C3BG/C3B (%)</th>
                                                    <th>L1</th>
                                                    <th>L3</th>
                                                    <th>L6</th>
                                                    <th>L8</th>
                                                    <th>L3/C3BG (%)</th>
                                                    <th>L8/L1 (%)</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach ($array_channel as $i)
                                                    @if($table['c3'][$i]!=0 && $table['c3b'][$i]!=0 && $table['c3bg'][$i]!=0)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td style="color:{{($table['c3'][$i]) >= ($table['c3_week'][$i]) ? 'green' : 'red'}}">{{$table['c3'][$i]}}</td>
                                                        <td style="color:{{$table['c3b'][$i] >= $table['c3b_week'][$i] ? 'green' : 'red'}}">{{$table['c3b'][$i]}}</td>
                                                        <td style="color:{{$table['c3bg'][$i] >= $table['c3bg_week'][$i] ? 'green' : 'red'}}">{{$table['c3bg'][$i]}}</td>
                                                        <td>{{($table['c3b'][$i] != 0) ? round($table['c3bg'][$i] * 100 / $table['c3b'][$i] , 2) : 0}}</td>
                                                        <td style="color:{{($table['l1'][$i]) >= ($table['l1_week'][$i]) ? 'green' : 'red'}}">{{$table['l1'][$i]}}</td>
                                                        <td style="color:{{($table['l3'][$i]) >= ($table['l3_week'][$i]) ? 'green' : 'red'}}">{{$table['l3'][$i]}}</td>
                                                        <td style="color:{{($table['l6'][$i]) >= ($table['l6_week'][$i]) ? 'green' : 'red'}}">{{$table['l6'][$i]}}</td>
                                                        <td style="color:{{($table['l8'][$i]) >= ($table['l8_week'][$i]) ? 'green' : 'red'}}">{{$table['l8'][$i]}}</td>
                                                        <td>{{($table['c3bg'][$i] != 0) ? round($table['l3'][$i] * 100 / $table['c3bg'][$i] , 2) : 0}}</td>
                                                        <td>{{($table['l1'][$i] != 0) ? round($table['l8'][$i] * 100 / $table['l1'][$i] , 2) : 0}}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                    <tr>
                                                        <th>Total</th>
                                                        <th>{{array_sum($table['c3'])}}</th>
                                                        <th>{{array_sum($table['c3b'])}}</th>
                                                        <th>{{array_sum($table['c3bg'])}}</th>
                                                        <th>{{(array_sum($table['c3b']) != 0) ? round(array_sum($table['c3bg']) * 100 / array_sum($table['c3b']) , 2) : 0}}</th>
                                                        <th>{{array_sum($table['l1'])}}</th>
                                                        <th>{{array_sum($table['l3'])}}</th>
                                                        <th>{{array_sum($table['l6'])}}</th>
                                                        <th>{{array_sum($table['l8'])}}</th>
                                                        <th>{{(array_sum($table['c3bg']) != 0) ? round(array_sum($table['l3']) * 100 / array_sum($table['c3bg']) , 2) : 0}}</th>
                                                        <th>{{(array_sum($table['l1']) != 0) ? round(array_sum($table['l8']) * 100 / array_sum($table['l1']) , 2) : 0}}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </article>
                                    </div>

                                    <div class="col-sm-12">
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
    <input type="hidden" name="source_id">
    <input type="hidden" name="marketer_id">
    <input type="hidden" name="team_id">
    <input type="hidden" name="campaign_id">
    <input type="hidden" name="subcampaign_id">
    <input type="hidden" name="registered_date">

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

    <style>
        .chart{
            height: 420px;
        }
        .flot-x-axis .flot-tick-label {
            white-space: nowrap;
            transform: translate(-9px, 0) rotate(-65deg);
            text-indent: -100%;
            transform-origin: top right;
            text-align: right !important;
            margin-bottom: 100px;

        }
    </style>
    <script src="{{ asset('js/reports/hour-report.js') }}"></script>

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {
            pageSetUp();
            initC3();
            initC3B();
            initC3BG();

            $('#search-form-channel-report').submit(function (e) {
                e.preventDefault();
                var url = $('#search-form-channel-report').attr('url');
                var source_id = $('select[name="source_id"]').val();
                var team_id = $('select[name="team_id"]').val();
                var marketer_id = $('select[name="marketer_id"]').val();
                var campaign_id = $('select[name="campaign_id"]').val();
                var subcampaign_id = $('select[name="subcampaign_id"]').val();
                var registered_date = $('.registered_date').text();

                $('input[name="source_id"]').val(source_id);
                $('input[name="team_id"]').val(team_id);
                $('input[name="marketer_id"]').val(marketer_id);
                $('input[name="campaign_id"]').val(campaign_id);
                $('input[name="subcampaign_id"]').val(subcampaign_id);
                $('input[name="registered_date"]').val(registered_date);

                $('.loading').show();
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        source_id       : source_id,
                        team_id         : team_id,
                        marketer_id     : marketer_id,
                        campaign_id     : campaign_id,
                        subcampaign_id  : subcampaign_id,
                        registered_date : registered_date,
                    }
                }).done(function (response) {
                    $('.loading').hide();
                    $('#wrapper_report').html(response);
                });
            });
        });

        function initChartChannel(item, data, arr_color){

            if (item.length) {
                $.plot(item, data,
                    {
                        series : {
                            lines : {
                                show : true,
                                lineWidth : 1,
                                fill : true,
                                fillColor : {
                                    colors : [{
                                        opacity : 0.1
                                    }, {
                                        opacity : 0.15
                                    }]
                                }
                            },
                            points : {
                                show : true
                            },
                            shadowSize : 0
                        },
                        xaxis : {
                            ticks: [
                                    @foreach ($array_channel as $key => $channel)
                                        [{{$key}},'{{$channel}}'],
                                    @endforeach
                            ]
                        },

                        yaxes : [{
                            min : 0
                        }],
                        grid : {
                            hoverable : true,
                            clickable : true,
                            tickColor : $chrt_border_color,
                            borderWidth : 0,
                            borderColor : $chrt_border_color,
                        },
                        colors : arr_color,
                    });
            }
            /* end site stats */
        }

        function initC3() {
            var item = $("#c3_chart");
            var data = [
                {   data :[
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3_week'][$channel]!=0)
                                    [{{$key}},{{$table['c3_week'][$channel]}}],
                                @endif
                            @endforeach
                    ],
                    label : "C3 Week",
                    color: "#FF8C00"
                },
                {   data :[
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3'][$channel]!=0)
                                    [{{$key}},{{$table['c3'][$channel]}}],
                                @endif
                            @endforeach
                    ],
                    label : "C3",
                    color: "#7CFC00"
                }
            ];

            initChartChannel(item, data);
            item.UseChannelTooltip();
        }

        function initC3B() {
            var item = $("#c3b_chart");
            var data = [
                {data : [
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3b_week'][$channel]!=0)
                                    [{{$key}},{{$table['c3b_week'][$channel]}}],
                                @endif
                            @endforeach
                    ],label : "C3B Week", color: "#FF8C00"},
                {data: [
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3b'][$channel]!=0)
                                    [{{$key}},{{$table['c3b'][$channel]}}],
                                @endif
                            @endforeach
                    ], label: "C3B", color: "#1E90FF"},
            ];

            initChartChannel(item, data);
            item.UseChannelTooltip();
        }

        function initC3BG() {
            var item = $("#c3bg_chart");
            var data = [
                {data : [
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3bg_week'][$channel]!=0)
                                    [{{$key}},{{$table['c3bg_week'][$channel]}}],
                                @endif
                            @endforeach
                    ],label : "C3BG Week", color: "#FF8C00"},
                {data: [
                            @foreach ($array_channel as $key => $channel)
                                @if($table['c3bg'][$channel]!=0)
                                    [{{$key}},{{$table['c3bg'][$channel]}}],
                                @endif
                            @endforeach
                    ], label: "C3BG", color: "#6A5ACD"},
            ];

            initChartChannel(item, data);
            item.UseChannelTooltip();
        }

    </script>
@endsection