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
    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {
            pageSetUp();
            initC3();
            initC3B();
            initC3BG();
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
                    @if($week == true)
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
                    @endif
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

