$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});

$(document).ready(function () {

    var d = new Date();
    var month = d.getMonth() + 1;
    if(month < 10){
        month = "0" + month.toString();
    }
    else {
        month = month.toString();
    }

    $('input[name="budget_month"]').val(month);
    $('input[name="quantity_month"]').val(month);
    $('input[name="quality_month"]').val(month);

    $('#sub_reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'right',
        minDate: moment().startOf('month'),
        maxDate:moment().endOf('month'),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
            "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);

    cb(start, end);

    set_title();

    $('li#month').click(function() {
        var month       = $(this).val();
        var dropdown    = $(this).closest('ul').siblings();
        dropdown.html(__arr_month[month - 1]);

        if(month < 10){
            month = "0" + month.toString();
        }
        else {
            month = month.toString();
        }

        var title       = $(this).parents('div.widget-toolbar').siblings('h2');
        var title_id    = title.attr('id');
        if(title_id == 'budget'){
            title.html('Budget in ' + dropdown.html());
            $('input[name="budget_month"]').val(month);
            get_budget(month);
        } else if (title_id == 'quantity'){
            title.html('Quantity in ' + dropdown.html());
            $('input[name="quantity_month"]').val(month);
            get_quantity(month);
        } else {
            title.html('Quality in ' + dropdown.html());
            $('input[name="quality_month"]').val(month);
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
                    "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithDots(y) + "</strong> (VND)");
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
                    "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong>");
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
                    "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong> (%)");
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
    $('#sub_reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
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

$(document).ready(function () {
    $('#source_id').change(function (e) {
        var url = $('#source_id').attr('data-url');
        var source_id = $('select[name="source_id"]').val();
        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                source_id: source_id
            }
        }).done(function (response) {
            $('#team_id').html(response.content_team);
            $("#team_id").select2();
            $('#marketer_id').html(response.content_marketer);
            $("#marketer_id").select2();
            $('#campaign_id').html(response.content_campaign);
            $("#campaign_id").select2();
            $('#subcampaign_id').html(response.content_subcampaign);
            $("#subcampaign_id").select2();
        });
    })

    $('#team_id').change(function (e) {
        var url = $('#team_id').attr('data-url');
        var team_id = $('select[name="team_id"]').val();

        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                team_id: team_id
            }
        }).done(function (response) {
            $('#marketer_id').html(response.content_marketer);
            $("#marketer_id").select2();
            $('#campaign_id').html(response.content_campaign);
            $("#campaign_id").select2();
            $('#subcampaign_id').html(response.content_subcampaign);
            $("#subcampaign_id").select2();
        });
    })

    $('#marketer_id').change(function (e) {
        var url = $('#marketer_id').attr('data-url');
        var creator_id = $('select[name="marketer_id"]').val();

        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                creator_id: creator_id
            }
        }).done(function (response) {
            $('#campaign_id').html(response.content_campaign);
            $("#campaign_id").select2();
            $('#subcampaign_id').html(response.content_subcampaign);
            $("#subcampaign_id").select2();
        });
    })

    $('#campaign_id').change(function (e) {
        var url = $('#campaign_id').attr('data-url');
        var campaign_id = $('select[name="campaign_id"]').val();
        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                campaign_id: campaign_id
            }
        }).done(function (response) {
            $('#subcampaign_id').html(response.content_subcampaign);
            $("#subcampaign_id").select2();
        });
    })

    $('#search-form-sub-report').submit(function (e) {
        e.preventDefault();
        var url             = $('#search-form-sub-report').attr('url');
        var source_id       = $('select[name="source_id"]').val();
        var team_id         = $('select[name="team_id"]').val();
        var marketer_id     = $('select[name="marketer_id"]').val();
        var campaign_id     = $('select[name="campaign_id"]').val();
        var subcampaign_id  = $('select[name="subcampaign_id"]').val();
        var registered_date = $('.registered_date').text();
        var budget_month    = $('input[name="budget_month"]').val();
        var quantity_month  = $('input[name="quantity_month"]').val();
        var quality_month   = $('input[name="quality_month"]').val();

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
            contentType: "application/json",
            dataType: "json",
            data: {
                source_id       : source_id,
                team_id         : team_id,
                marketer_id     : marketer_id,
                campaign_id     : campaign_id,
                subcampaign_id  : subcampaign_id,
                budget_month    : budget_month,
                quantity_month  : quantity_month,
                quality_month   : quality_month,
            }
        }).done(function (response) {
            set_budget_chart(response.budget);
            set_quantity_chart(response.quantity);
            set_quality_chart(response.quality);
        });

        $('.loading').hide();
    })

    $('#budget_chk input[type=checkbox]').change(function (e){
        var month = $('input[name="budget_month"]').val();
        get_budget(month);
    })

    $('#quantity_chk input[type=checkbox]').change(function (e){
        var month = $('input[name="quantity_month"]').val();
        get_quantity(month);
    })

    $('#quality_chk input[type=checkbox]').change(function (e){
        var month = $('input[name="quality_month"]').val();
        get_quality(month);
    })

});

function get_budget(month) {
    var url             = $('input[name="budget_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var registered_date = $('.registered_date').text();

    $('input[name="source_id"]').val(source_id);
    $('input[name="team_id"]').val(team_id);
    $('input[name="marketer_id"]').val(marketer_id);
    $('input[name="campaign_id"]').val(campaign_id);
    $('input[name="subcampaign_id"]').val(subcampaign_id);
    $('input[name="registered_date"]').val(registered_date);

    var data = {};
    data.source_id          = source_id;
    data.team_id            = team_id;
    data.marketer_id        = marketer_id;
    data.campaign_id        = campaign_id;
    data.subcampaign_id     = subcampaign_id;
    data.month              = month;

    $.get(url, data, function (data) {
        set_budget_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function get_quantity(month) {
    var url             =  $('input[name="quantity_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var registered_date = $('.registered_date').text();

    $('input[name="source_id"]').val(source_id);
    $('input[name="team_id"]').val(team_id);
    $('input[name="marketer_id"]').val(marketer_id);
    $('input[name="campaign_id"]').val(campaign_id);
    $('input[name="subcampaign_id"]').val(subcampaign_id);
    $('input[name="registered_date"]').val(registered_date);

    var data = {};
    data.source_id          = source_id;
    data.team_id            = team_id;
    data.marketer_id        = marketer_id;
    data.campaign_id        = campaign_id;
    data.subcampaign_id     = subcampaign_id;
    data.month              = month;

    $.get(url, data, function (data) {

        set_quantity_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function get_quality(month) {
    var url             =  $('input[name="quality_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var registered_date = $('.registered_date').text();

    $('input[name="source_id"]').val(source_id);
    $('input[name="team_id"]').val(team_id);
    $('input[name="marketer_id"]').val(marketer_id);
    $('input[name="campaign_id"]').val(campaign_id);
    $('input[name="subcampaign_id"]').val(subcampaign_id);
    $('input[name="registered_date"]').val(registered_date);

    var data = {};
    data.source_id          = source_id;
    data.team_id            = team_id;
    data.marketer_id        = marketer_id;
    data.campaign_id        = campaign_id;
    data.subcampaign_id     = subcampaign_id;
    data.month              = month;

    $.get(url, data, function (data) {
        set_quality_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function set_budget_chart(data) {
    $('span#me_re').text(data.me_re);

    var item        = $("#budget_chart");
    var dataSet     = [];
    var arr_color   = [];

    var data_l1     = {data : jQuery.parseJSON(data.l1),      label : "L1"};
    var data_l3     = {data : jQuery.parseJSON(data.l3),      label : "L3"};
    var data_l6     = {data : jQuery.parseJSON(data.l6),      label : "L6"};
    var data_l8     = {data : jQuery.parseJSON(data.l8),      label : "L8"};
    var data_c3b    = {data : jQuery.parseJSON(data.c3b),     label : "C3B"};
    var data_c3bg   = {data : jQuery.parseJSON(data.c3bg),    label : "C3BG"};
    var data_me     = {data : jQuery.parseJSON(data.me),      label : "ME"};
    var data_re     = {data : jQuery.parseJSON(data.re),      label : "RE"};

    var lst_checkbox = $('#budget_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function(index, checkbox) {
        $label = $(checkbox).val();
        if($label == 'L1'){
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if($label == 'L3'){
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if($label == 'L6'){
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if($label == 'L8'){
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if($label == 'C3B'){
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if($label == 'C3BG'){
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
        if($label == 'ME'){
            dataSet.push(data_me);
            arr_color.push('#000')
        }
        if($label == 'RE'){
            dataSet.push(data_re);
            arr_color.push('#008000')
        }
    });

    initChart(item, dataSet, arr_color);
    $("#budget_chart").UseBudgetTooltip();

}

function set_quantity_chart(data) {
    var item    = $("#quantity_chart");
    var dataSet     = [];
    var arr_color   = [];

    var data_l1     = {data : jQuery.parseJSON(data.l1),      label : "L1"};
    var data_l3     = {data : jQuery.parseJSON(data.l3),      label : "L3"};
    var data_l6     = {data : jQuery.parseJSON(data.l6),      label : "L6"};
    var data_l8     = {data : jQuery.parseJSON(data.l8),      label : "L8"};
    var data_c3b    = {data : jQuery.parseJSON(data.c3b),     label : "C3B"};
    var data_c3bg   = {data : jQuery.parseJSON(data.c3bg),    label : "C3BG"};

    var lst_checkbox = $('#quantity_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function(index, checkbox) {
        $label = $(checkbox).val();
        if($label == 'L1'){
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if($label == 'L3'){
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if($label == 'L6'){
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if($label == 'L8'){
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if($label == 'C3B'){
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if($label == 'C3BG'){
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
    });

    initChart(item, dataSet, arr_color);
    $("#budget_chart").UseQuantityTooltip();

}

function set_quality_chart(data) {
    var item    = $("#quality_chart");
    var dataSet     = [];
    var arr_color   = [];

    var data_l3_c3b     = {data : jQuery.parseJSON(data.l3_c3b),      label : "L3/C3B"};
    var data_l3_c3bg    = {data : jQuery.parseJSON(data.l3_c3bg),     label : "L3/C3BG"};
    var data_l3_l1      = {data : jQuery.parseJSON(data.l3_l1),       label : "L3/L1"};
    var data_l1_c3bg    = {data : jQuery.parseJSON(data.l1_c3bg),     label : "L1/C3BG"};
    var data_c3bg_c3b   = {data : jQuery.parseJSON(data.c3bg_c3b),    label : "C3BG/C3B"};
    var data_l6_l3      = {data : jQuery.parseJSON(data.l6_l3),       label : "L6/L3"};
    var data_l8_l6      = {data : jQuery.parseJSON(data.l8_l6),       label : "L8/L6"};

    var lst_checkbox = $('#quality_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function(index, checkbox) {
        $label = $(checkbox).val();
        if($label == 'L3/C3B'){
            dataSet.push(data_l3_c3b);
            arr_color.push('#800000');
        }
        if($label == 'L3/C3BG'){
            dataSet.push(data_l3_c3bg);
            arr_color.push('#6A5ACD')
        }
        if($label == 'L3/L1'){
            dataSet.push(data_l3_l1);
            arr_color.push('#808080')
        }
        if($label == 'L1/C3BG'){
            dataSet.push(data_l1_c3bg);
            arr_color.push('#7CFC00')
        }
        if($label == 'C3BG/C3B'){
            dataSet.push(data_c3bg_c3b);
            arr_color.push('#FF8C00')
        }
        if($label == 'L6/L3'){
            dataSet.push(data_l6_l3);
            arr_color.push('#1E90FF')
        }
        if($label == 'L8/L6'){
            dataSet.push(data_l8_l6);
            arr_color.push('#1E90FF')
        }
    });


    initChart(item, dataSet, arr_color);
    $("#quality_chart").UseQualityTooltip();
}

function getDate(date){
    var d = new Date(date);
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    curr_month++;

    return curr_date + "/" + curr_month;

}


/* chart colors default */

var $chrt_border_color = "#efefef";
var $chrt_grid_color = "#DDD";
/* site stats chart */

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
                    mode: "time",
                    timeformat: "%d/%m",
                    ticks : 14
                },

                yaxes : [{
                    ticks : 10,
                    min : 0,
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


