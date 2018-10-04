@include('pages.lists.table_report_channel')

<div class="col-sm-12">
    <article class="col-sm-12 col-md-12">
    @component('components.jarviswidget',
    ['id' => 'c3bg', 'icon' => 'fa-line-chart', 'title' => "C3BG ", 'dropdown' => 'false'])
        <!-- widget content -->
            <div class="widget-body no-padding flot_channel">
                <div id="c3bg_chart" class="chart has-legend"></div>
            </div>
        @endcomponent
    </article>
    <article class="col-sm-12 col-md-12">
    @component('components.jarviswidget',
    ['id' => 'c3b', 'icon' => 'fa-line-chart', 'title' => "C3B ", 'dropdown' => 'false'])
        <!-- widget content -->
            <div class="widget-body no-padding flot_channel">
                <div id="c3b_chart" class="chart has-legend"></div>
            </div>
        @endcomponent
    </article>

    <article class="col-sm-12 col-md-12">
    @component('components.jarviswidget',
    ['id' => 'chart_number', 'icon' => 'fa-line-chart', 'title' => "Column Chart", 'dropdown' => 'false'])
        <!-- widget content -->
            <div class="widget-body no-padding">
                <div id="number_chart" class="chart has-legend"></div>
            </div>
        @endcomponent
    </article>

    <article class="col-sm-12 col-md-12">
    @component('components.jarviswidget',
    ['id' => 'chart_reason', 'icon' => 'fa-line-chart', 'title' => "Reason Chart", 'dropdown' => 'false'])
        <!-- widget content -->
            <div class="widget-body no-padding">
                <div id="reason_chart" class="chart has-legend"></div>
            </div>
        @endcomponent
    </article>
    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {
            pageSetUp();
            // initC3();
            initC3B();
            initC3BG();

            $('.ads__show').click(function () {
                var source_id = $('select[name="source_id"]').val();
                var team_id = $('select[name="team_id"]').val();
                var marketer_id = $('select[name="marketer_id"]').val();
                var campaign_id = $('select[name="campaign_id"]').val();
                var subcampaign_id = $('select[name="subcampaign_id"]').val();
                var registered_date = $('.registered_date').text();
                var type = $('select[name="type"]').val();

                var $this = $(this);
                var channel_name = $this.data('channel_name');
                var api_channel = $this.data('api_channel');
                var tr_channel = $(this).parent().parent();
                var ads_detail = tr_channel.nextUntil('.tr_channel');

                if (ads_detail && ads_detail.hasClass('ads_show')){
                    $this.html('<i class="fa fa-plus-circle"></i>');
                    ads_detail.addClass('ads_hide');
                    ads_detail.removeClass('ads_show');
                    ads_detail.hide();
                }
                else if (ads_detail && ads_detail.hasClass('ads_hide')){
                    $this.html('<i class="fa fa-minus-circle"></i>');
                    ads_detail.addClass('ads_show');
                    ads_detail.removeClass('ads_hide');
                    ads_detail.show();
                }
                else{
                    $this.html('<i class="fa fa-minus-circle"></i>');

                    $('.loading').show();
                    $.ajax({
                        url: api_channel,
                        type: 'GET',
                        data: {
                            channel_name : channel_name,
                            source_id       : source_id,
                            team_id         : team_id,
                            marketer_id     : marketer_id,
                            campaign_id     : campaign_id,
                            subcampaign_id  : subcampaign_id,
                            registered_date : registered_date,
                            type : type,
                        }
                    }).done(function (response) {
                        $('.loading').hide();
                        $(tr_channel).after(response);
                    });


                }
            })

            var _data = [
                {color: '#ff00aa', data: [[0, {{$array_sum['c3b']}}]]},
                {color: 'red', data: [[1, {{$array_sum['c3bg']}}]]},
                {color: 'yellow', data: [[2, {{$array_sum['l1']}}]]},
                {color: 'orange', data: [[3, {{$array_sum['l3']}}]]},
                {color: 'blue', data: [[4, {{$array_sum['l6']}}]]},
                {color: '#000000', data: [[5, {{$array_sum['l8']}}]]}
            ];

            var number_chart = $.plot("#number_chart", _data, {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.5,
                        align: "center",
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0,
                    ticks: [
                        [0, "C3B"],
                        [1, "C3BG"],
                        [2, "L1"],
                        [3, "L3"],
                        [4, "L6"],
                        [5, "L8"]
                    ],
                    font:{
                        size:14,
                        color: "#333"
                    }
                },
                yaxes : [{
                    min : 0
                }],
                grid : {
                    show : true,
                    hoverable : true,
                    clickable : false,
                    borderColor : "#efefef",
                },
                tooltip : true,
                tooltipOpts : {
                    content : "<div>%y</div>",
                    defaultTheme : false
                },
                colors: ["#FF8C00", "#666", "#BBB"]
            });

            $.each(number_chart.getData(), function(i, ele){
                var el = ele.data[0];
                var o = number_chart.pointOffset({x: el[0], y: el[1]});
                $('<div class="data-point-label">' + el[1] + '</div>').css( {
                    position: 'absolute',
                    left: o.left - 15,
                    top: o.top - 20,
                    display: 'none'
                }).appendTo(number_chart.getPlaceholder()).fadeIn('slow');
            });

            var  ds_reason = [
                { label: "C3A_Duplicated",  data: {{$data_reason['C3A_Duplicated']}}, color: '#e1ab0b'},
                { label: "C3B_Under18",  data: {{$data_reason['C3B_Under18']}}, color: '#fe0000'},
                { label: "C3B_Duplicated15Days",  data: {{$data_reason['C3B_Duplicated15Days']}}, color: '#93b40f'},
                { label: "C3A_Test",  data: {{$data_reason['C3A_Test']}}, color: '#99FF99'},
                { label: "C3B_SMS_Error",  data: {{$data_reason['C3B_SMS_Error']}}, color: '#006666'}

            ];

            $.plot("#reason_chart", ds_reason , {
                series: {
                    pie: {
                        show : true,
                        innerRadius : 0.5,
                        radius : 1,
                        threshold: 0.1,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: function(label, series){
                                return "<div style='font-size:12px;color:#333;font-weight: 600'>"
                                    + Math.round(series.percent) + "%</div>";
                            },
                            background: {
                                opacity: 0.8
                            }
                        }
                    }
                },
                legend: {
                    show : true,
                    noColumns : 1,
                    labelBoxBorderColor : "#000",
                    margin : [20, 20],
                    backgroundColor : "#efefef",
                    backgroundOpacity : 1,
                    labelFormatter: function (label, series) {
                        return '<div ' +
                            'style="font-size:15px;padding:3px;color:#333">' +
                            label +': <strong>' + series.data[0][1] + ' - ' + Math.round(series.percent)+'%</strong></div>';
                    }
                },
                grid : {
                    hoverable : true
                },
                tooltip : true,
                tooltipOpts : {
                    cssClass: "flotTip",
                    content: "%s: %p.0%",
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                },
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

        {{--function initC3() {--}}
            {{--var item = $("#c3_chart");--}}
            {{--var data = [--}}
                    {{--@if($week == true)--}}
                {{--{   data :[--}}
                            {{--@foreach ($array_channel as $key => $channel)--}}
                            {{--@if($table['c3_week'][$channel]!=0)--}}
                        {{--[{{$key}},{{$table['c3_week'][$channel]}}],--}}
                        {{--@endif--}}
                        {{--@endforeach--}}
                    {{--],--}}
                    {{--label : "C3 Week",--}}
                    {{--color: "#FF8C00"--}}
                {{--},--}}
                    {{--@endif--}}
                {{--{   data :[--}}
                            {{--@foreach ($array_channel as $key => $channel)--}}
                            {{--@if($table['c3'][$channel]!=0)--}}
                        {{--[{{$key}},{{$table['c3'][$channel]}}],--}}
                        {{--@endif--}}
                        {{--@endforeach--}}
                    {{--],--}}
                    {{--label : "C3",--}}
                    {{--color: "#7CFC00"--}}
                {{--}--}}
            {{--];--}}

            {{--initChartChannel(item, data);--}}
            {{--item.UseChannelTooltip();--}}
        {{--}--}}

        function initC3B() {
            var item = $("#c3b_chart");
            var data = [
                    @if($week == true)
                {data : [
                            @foreach ($array_channel as $key => $channel)
                            @if($table['c3b_week'][$channel]!=0)
                        [{{$key}},{{$table['c3b_week'][$channel]}}],
                        @endif
                        @endforeach
                    ],label : "C3B Week", color: "#FF8C00"},
                    @endif

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
                    @if($week == true)
                {data : [
                            @foreach ($array_channel as $key => $channel)
                            @if($table['c3bg_week'][$channel]!=0)
                        [{{$key}},{{$table['c3bg_week'][$channel]}}],
                        @endif
                        @endforeach
                    ],label : "C3BG Week", color: "#FF8C00"},
                    @endif
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

</div>

