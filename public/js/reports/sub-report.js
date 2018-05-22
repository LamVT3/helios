$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});

$(document).ready(function () {
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
    }, cb);

    cb(start, end);

    set_title();

    $('li#month').click(function() {
        var month       = $(this).val();
        var dropdown    = $(this).closest('ul').siblings();
        dropdown.html(__arr_month[month - 1]);

        var title       = $(this).parents('div.widget-toolbar').siblings('h2');
        var title_id    = title.attr('id');
        if(title_id == 'budget'){
            title.html('Budget in ' + dropdown.html());
            get_budget(month);
        } else if (title_id == 'quantity'){
            title.html('Quantity in ' + dropdown.html());
            get_quantity(month);
        } else {
            title.html('Quality in ' + dropdown.html());
            get_quality(month);
        }

    });
});

function set_title(){
    var d = new Date();
    var current_month = d.getMonth();
    var dropdown = $('button#dropdown');
    dropdown.html(__arr_month[current_month]);
    $('h2#budget').html('Budget in ' + dropdown.html());
    $('h2#quantity').html('Quantity in ' + dropdown.html());
    $('h2#quality').html('Quality in ' + dropdown.html());

    $('li#month').click();
}

var previousPoint = null, previousLabel = null;
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

$.fn.UseBudgetTooltip = function () {
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
                    "<strong>" + item.series.label + "</strong><br>" + monthNames[month] + " : <strong>" + numberWithDots(y) + "</strong> (VND)");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseQuantityTooltip = function () {
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
                    "<strong>" + item.series.label + "</strong><br>" + monthNames[month] + " : <strong>" + numberWithCommas(y) + "</strong>");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseQualityTooltip = function () {
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
                    "<strong>" + item.series.label + "</strong><br>" + monthNames[month] + " : <strong>" + y + "</strong> (%)");
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

function cb(start, end) {
    $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
}

function numberWithCommas(number) {
    var parts = number.toFixed().split(".");;
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function numberWithDots(number) {
    var parts = number.toFixed().split(".");;
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(".");
}

