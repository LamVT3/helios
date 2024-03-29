$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});

$(document).ready(function () {

    var start = moment();
    var end = moment();

    function reportrange_span(start, end) {
        $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
    }

    reportrange_span(start, end);

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'right',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
            "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, reportrange_span);
});

var previousPoint = null, previousLabel = null;
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function getDate(date) {
    var d = new Date(date);
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    curr_month++;

    return curr_date + "/" + curr_month;

}

$.fn.UseTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var month = new Date(x).getMonth();

                tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong>";

                showTooltip(item.pageX, item.pageY, color, tooltip);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseChannelTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;

                showTooltip(item.pageX,
                    item.pageY,
                    color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label +": "+  y );
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseC3Tooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var month = new Date(x).getMonth();

                showTooltip(item.pageX,
                    item.pageY,
                    color,
                    "<strong>" + item.series.label + "</strong><br>" + y );
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseC3BTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var month = new Date(x).getMonth();

                showTooltip(item.pageX,
                    item.pageY,
                    color,
                    "<strong>" + item.series.label + "</strong><br>" + y );
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseC3BGTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var month = new Date(x).getMonth();

                showTooltip(item.pageX,
                    item.pageY,
                    color,
                    "<strong>" + item.series.label + "</strong><br>" + y );
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

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

var start = moment();
var end = moment();

/* chart colors default */

var $chrt_border_color = "#efefef";

function initChart(item, data, arr_color){

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
                    mode: "hour",
                    timeformat: "%H",
                    ticks : 23
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

function initChartA(item, data, color) {
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
            timeformat: "%d/%m",
            ticks : 14
        },
        yaxes: [{
            ticks: 10,
            min: 0,
        }],
        grid: {
            hoverable: true,
            // clickable : true,
            tickColor: $chrt_border_color,
            borderWidth: 0,
            borderColor: $chrt_border_color,
        },
        colors: color,
    };

    if (item.length) {
        $.plot(item, data, option);
    }
    /* end site stats */
}

function get_chart(checkbox, element, url, month) {
    var data = {};
    var source_id = $('select[name="source_id"]').val();
    var team_id = $('select[name="team_id"]').val();
    var marketer_id = $('select[name="marketer_id"]').val();
    var campaign_id = $('select[name="campaign_id"]').val();
    var subcampaign_id = $('select[name="subcampaign_id"]').val();
    data.month = month;
    data.source_id = source_id;
    data.team_id = team_id;
    data.marketer_id = marketer_id;
    data.campaign_id = campaign_id;
    data.subcampaign_id = subcampaign_id;

    element.parent().parent().parent().parent().find('.loading').css("display", "block");
    $.get(url, data, function (rs) {
        set_chart(checkbox, element, rs);
        element.parent().parent().parent().parent().find('.loading').css("display", "none");
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

var arr_color = ["#800000", "#6A5ACD", "#808080", "#7CFC00", "#FF8C00", "#1E90FF", "#000", "#008000",
                 "#FFCCCC", "#999933", "#FF6600", "#9999FF", "#FF66FF", "#000088", "#000022", "#99FF99",
                 "#33FF66", "#FFCC33", "#CCCC00", "#CC0099", "#990099", "#FF3333", "#009999", "#006666"
                ];

function set_chart(checkbox, element, data) {
    var dataSet = [];
    var colorSet = [];

    var lst_checkbox = checkbox.find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        dataSet.push({data: jQuery.parseJSON(data[$label]), label: $label+"h"});
        colorSet.push(arr_color[$label]);
    });

    initChartA(element, dataSet, colorSet);
    element.UseTooltip();

}
