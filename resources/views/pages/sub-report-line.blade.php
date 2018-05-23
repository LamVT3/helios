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

                        <form id="search-form-sub-report" class="smart-form" action="#"
                              url="{!! route('line-chart.filter') !!}">
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
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                        <i class="fa fa-filter"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="loading" style="display: none">
                            <div class="col-md-12 text-center">
                                <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <article class="col-sm-12 col-md-12">
                            @component('components.jarviswidget',
                            ['id' => 'budget', 'icon' => 'fa-line-chart', 'title' => "Budget in ", 'dropdown' => 'true'])
                                <!-- widget content -->
                                    <div class="widget-body no-padding">

                                        <div class="widget-body-toolbar bg-color-white smart-form">
                                            <p class="text-danger text-right"><strong>ME / RE: </strong><span id="me_re">{{ $budget["me_re"]  or '0'}}</span>%</p>
                                        </div>
                                        <div id="budget_chart" class="chart has-legend"></div>
                                    </div>
                                @endcomponent
                            </article>

                            <article class="col-sm-12 col-md-12">
                            @component('components.jarviswidget',
                            ['id' => 'quantity', 'icon' => 'fa-line-chart', 'title' => "Quantity in ", 'dropdown' => 'true'])
                                <!-- widget content -->
                                    <div class="widget-body no-padding">
                                        <div class="widget-body-toolbar bg-color-white smart-form">

                                        </div>
                                        <div id="quantity_chart" class="chart has-legend"></div>
                                    </div>
                                @endcomponent
                            </article>

                            <article class="col-sm-12 col-md-12">
                            @component('components.jarviswidget',
                            ['id' => 'quality', 'icon' => 'fa-line-chart', 'title' => "Quality in ", 'dropdown' => 'true'])
                                <!-- widget content -->
                                    <div class="widget-body no-padding">

                                        <div class="widget-body-toolbar bg-color-white smart-form">
                                        </div>
                                        <div id="quality_chart" class="chart has-legend"></div>
                                    </div>
                                @endcomponent
                            </article>

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
<input type="hidden" name="source_id">
<input type="hidden" name="marketer_id">
<input type="hidden" name="team_id">
<input type="hidden" name="campaign_id">
<input type="hidden" name="subcampaign_id">
<input type="hidden" name="registered_date">
<input type="hidden" name="page_size" value="{{$page_size}}">
<input type="hidden" name="budget_url" value="{{route('get-budget')}}">
<input type="hidden" name="quantity_url" value="{{route('get-quantity')}}">
<input type="hidden" name="quality_url" value="{{route('get-quality')}}">
<input type="hidden" name="budget_month">
<input type="hidden" name="quantity_month">
<input type="hidden" name="quality_month">
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

<script src="{{ asset('js/reports/sub-report.js') }}"></script>

<script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
$(document).ready(function () {
    initBudget();
    initQuantity();
    initQuality();

});

function initBudget(){
    var item = $("#budget_chart");
    var data = [
        {data : {{ $budget["me"] }},    label : "ME"},
        {data : {{ $budget["re"] }},    label : "RE"},
        {data : {{ $budget["c3b"] }},   label : "C3B"},
        {data : {{ $budget["c3bg"] }},  label : "C3BG"},
        {data : {{ $budget["l1"] }},    label : "L1"},
        {data : {{ $budget["l3"] }},    label : "L3"},
        {data : {{ $budget["l6"] }},    label : "L6"},
        {data : {{ $budget["l8"] }},    label : "L8"},
    ];

    initChart(item, data);
    item.UseBudgetTooltip();
}

function initQuantity(){
    var item = $("#quantity_chart");
    var data =  [
        {data : {{ $quantity["c3b"] }},     label : "C3B"},
        {data : {{ $quantity["c3bg"] }},    label : "C3BG"},
        {data : {{ $quantity["l1"] }},      label : "L1"},
        {data : {{ $quantity["l3"] }},      label : "L3"},
        {data : {{ $quantity["l6"] }},      label : "L6"},
        {data : {{ $quantity["l8"] }},      label : "L8"},
    ];

    initChart(item, data);
    $("#quantity_chart").UseQuantityTooltip();
}

function initQuality(){
    var item = $("#quality_chart");
    var data =  [
        {data : {{ $quality["l3_c3b"] }},      label : "L3/C3B"},
        {data : {{ $quality["l3_c3bg"] }},     label : "L3/C3BG"},
        {data : {{ $quality["l3_l1"] }},       label : "L3/L1"},
        {data : {{ $quality["l1_c3bg"] }},     label : "L1/C3BG"},
        {data : {{ $quality["c3bg_c3b"] }},    label : "C3BG/C3B"},
        {data : {{ $quality["l6_l3"] }},       label : "L6/L3"},
        {data : {{ $quality["l8_l6"] }},       label : "L8/L6"},
    ];

    initChart(item, data);
    $("#quality_chart").UseQualityTooltip();
}

    </script>
    @include('components.script-jarviswidget')
@endsection