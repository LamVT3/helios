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
                                    {{--<div class="row">--}}
                                    {{--<div id="sub_reportrange" class="pull-left"--}}
                                    {{--style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">--}}
                                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;--}}
                                    {{--<span class="registered_date"></span> <b class="caret"></b>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
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
                                                    <th>C3</th>
                                                    <th>C3B</th>
                                                    <th>C3BG</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @for($i = 0; $i < 24; $i++)
                                                    <tr>
                                                        <td>{{$i}}h</td>
                                                        <td>{{$c3[$i]}}</td>
                                                        <td>{{$c3b[$i]}}</td>
                                                        <td>{{$c3bg[$i]}}</td>
                                                    </tr>
                                                @endfor
                                                    <tr>
                                                        <th>Total</th>
                                                        <th>{{array_sum($c3)}}</th>
                                                        <th>{{array_sum($c3b)}}</th>
                                                        <th>{{array_sum($c3bg)}}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </article>
                                    </div>

                                    <div class="col-sm-8">
                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'budget', 'icon' => 'fa-line-chart', 'title' => "C3 ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'quantity', 'icon' => 'fa-line-chart', 'title' => "C3B ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3b_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3bg', 'icon' => 'fa-line-chart', 'title' => "C3BG ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3bg_chart" class="chart has-legend"></div>
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

        var arr_color = ["#800000", "#6A5ACD", "#808080", "#7CFC00", "#FF8C00", "#1E90FF", "#000", "#008000"];
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {
            initC3();
            initC3B();
            initC3BG();

        });

        function initC3() {
            var item = $("#c3_chart");
            var data = [
                {data: {{$c3_chart}}, label: "C3"},
            ];

            initChart(item, data, ["#7CFC00"]);
            item.UseC3Tooltip();
        }

        function initC3B() {
            var item = $("#c3b_chart");
            var data = [
                {data: {{$c3b_chart}}, label: "C3B"},
            ];

            initChart(item, data, ["#1E90FF"]);
            $("#c3b_chart").UseC3BTooltip();
        }

        function initC3BG() {
            var item = $("#c3bg_chart");
            var data = [
                {data: {{$c3bg_chart}}, label: "C3BG"},
            ];

            initChart(item, data, ["#6A5ACD"]);
            $("#c3bg_chart").UseC3BGTooltip();
        }

    </script>
    @include('components.script-jarviswidget')
@endsection