<script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
<script>

    $(function(){
        $('.nav-tabs a[href="#statistic"]').on('show.bs.tab', function () {
            initStatisticChart();
        });
    })

    function initStatisticChart() {
        var url = $('input[id=statistic_chart_url]').val();
        var year = $('#statistic_chart_year').val();
        var month = $('#statistic_chart_month').val();
        var noLastMonth = $('#statistic_chart_noMonth').val();

        $.get(url, {year: year, month: month, noLastMonth: noLastMonth}, function (data) {
            // console.log(data);
            setStatisticChart(data, noLastMonth);
        }).fail( function (e) {
            console.log(e);
            alert('Cannot connect to server. Please try again later.');
        });
    }

    function setStatisticChart(data, noLastMonth){
        var item = $("#statistic_report_chart");
        var dataSet = [];
        var arr_color = [];

        var data_c3 = {data: jQuery.parseJSON(data.c3b), label: "No C3 produced/tháng"};
        var data_c3b_price = {data: jQuery.parseJSON(data.c3b_price), label: "Price of C3B produced"};
        var data_l3_c3b = {data: jQuery.parseJSON(data.l3_c3bg), label: "L3/C3B transfered", yaxis: 2};

        var lst_checkbox = $('#statistic_chk').find('input[type=checkbox]:checked');
        jQuery.each(lst_checkbox, function (index, checkbox) {
            $label = $(checkbox).val();
            if ($label == 'c3b') {
                dataSet.push(data_c3);
                arr_color.push('#0000CD');
            }
            if ($label == 'c3b_price') {
                dataSet.push(data_c3b_price);
                arr_color.push('#FF0000')
            }
            if ($label == 'l3_c3bg') {
                dataSet.push(data_l3_c3b);
                arr_color.push('#FF8C00')
            }
        });

        initChart(item, dataSet, arr_color, noLastMonth);
    }

    function initChart(item, data, arr_color, noLastMonth) {
        var option = {
            series: {
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.1
                        }, {
                            opacity: 0.15
                        }]
                    }
                },
                points: {
                    show: true
                },
                shadowSize: 0
            },
            xaxis : {
                mode: "time",
                timeformat: "%m/%Y",
            },
            yaxes:  [{
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10,
                tick: 10,
                min: 0
            }, {
                position: "right",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 50,
                tick: 10,
                min: 0
            }
            ],
            grid: {
                hoverable: true,
                clickable : true,
                tickColor: "#efefef",
                borderWidth: 0,
                borderColor: "#efefef",
                margin: {
                    right: 20
                }
            },
            // tooltip : true,
            // tooltipOpts : {
            //     content : "<b>%y C3</b> (%x)",
            //     defaultTheme : false,
            //     shifts: {
            //         x: -50,
            //         y: 20
            //     }
            // },
            colors: arr_color,
        };

        if (item.length) {
            $.plot(item, data, option);
            setTooltip(item);
        }
        /* end site stats */
    }
    var previousPoint = null, previousLabel = null;
    function setTooltip(chart) {
        $(chart).bind("plothover", function (event, pos, item) {
            if (item) {
                if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                    previousPoint = item.dataIndex;
                    previousLabel = item.series.label;
                    $("#tooltip").remove();

                    var x = item.datapoint[0];
                    var y = item.datapoint[1];

                    var color = item.series.color;
                    var month = new Date(x).getMonth();

                    var tooltip = ''
                    if (item.series.label == 'No C3 produced/tháng') {
                        tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong>";
                    } else if (item.series.label == 'Price of C3B produced') {
                        tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong> (VND)"
                    } else {
                        tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong> (%)";
                    }

                    showTooltip(item.pageX, item.pageY, color, tooltip);
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    };

    function numberWithCommas(number) {
        var parts = number.toFixed().split(".");
        ;
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

    function numberWithDots(number) {
        var parts = number.toFixed().split(".");
        ;
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return parts.join(".");
    }

    function showTooltip(x, y, color, contents) {

        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y - 40,
            left: x - 40,
            border: '2px solid ' + color,
            padding: '3px',
            'font-size': '9px',
            'border-radius': '5px',
            'background-color': '#fff',
            'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            opacity: 0.9
        }).appendTo("body").fadeIn(200);
    }

    function getDate(date) {
        var d = new Date(date);
        var curr_month = d.getMonth();
        var curr_year = d.getFullYear();
        curr_month++;
        if(curr_month < 10){
            curr_month = '0' + curr_month;
        }

        return curr_month + "/" + curr_year;

    }

</script>