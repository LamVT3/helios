$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});

$(document).ready(function () {

    var d = new Date();
    var month = d.getMonth() + 1;
    if (month < 10) {
        month = "0" + month.toString();
    }
    else {
        month = month.toString();
    }

    $('input[name="budget_month"]').val(month);
    $('input[name="quantity_month"]').val(month);
    $('input[name="quality_month"]').val(month);
    $('input[name="C3AC3B_month"]').val(month);

    $('#sub_reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'right',
        minDate: moment().startOf('month'),
        maxDate: moment().endOf('month'),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            "This Week": [moment().startOf("isoWeek"), moment().endOf("isoWeek")],
            "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);

    cb(start, end);

    set_title();

    $('li#month').click(function () {
        var month = $(this).val();
        var dropdown = $(this).closest('ul').siblings();
        dropdown.html(__arr_month[month - 1]);

        if (month < 10) {
            month = "0" + month.toString();
        }
        else {
            month = month.toString();
        }

        var title = $(this).parents('div.widget-toolbar').siblings('h2');
        var title_id = title.attr('id');
        if (title_id == 'budget') {
            title.html('Budget in ' + dropdown.html());
            $('input[name="budget_month"]').val(month);
            get_budget(month);
        } else if (title_id == 'quantity') {
            title.html('Quantity in ' + dropdown.html());
            $('input[name="quantity_month"]').val(month);
            get_quantity(month);
        } else if (title_id == 'C3A-C3B') {
            title.html('C3A-C3B Report in ' + dropdown.html());
            $('input[name="C3AC3B_month"]').val(month);
            get_C3AC3B(month);
        } else {
            title.html('Quality in ' + dropdown.html());
            $('input[name="quality_month"]').val(month);
            get_quality(month);
        }
    });

    $('input#currency').click(function (e) {
        var unit = $(this).val();
        $('#currency_unit').val(unit);
        filterSubReport();
    })

});

function set_title() {
    var d = new Date();
    var current_month = d.getMonth();
    var current_year = d.getFullYear();
    var dropdown = $('button#dropdown');
    dropdown.html(__arr_month[current_month]);
    $('h2#budget').html('Budget in ' + dropdown.html());
    $('h2#quantity').html('Quantity in ' + dropdown.html());
    $('h2#quality').html('Quality in ' + dropdown.html());
    $('h2#C3A-C3B').html('C3A-C3B Report in ' + dropdown.html());

    $('h2#budget_by_weeks').html('Budget in ' + current_year);
    $('h2#quantity_by_weeks').html('Quantity in ' + current_year);
    $('h2#quality_by_weeks').html('Quality in ' + current_year);
    $('h2#C3A-C3B_by_weeks').html('C3A-C3B Report in ' + current_year);

    $('h2#budget_by_months').html('Budget in ' + current_year);
    $('h2#quantity_by_months').html('Quantity in ' + current_year);
    $('h2#quality_by_months').html('Quality in ' + current_year);
    $('h2#C3A-C3B_by_months').html('C3A-C3B Report in ' + current_year);

    $('li#month').click();
}

var previousPoint = null, previousLabel = null;
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

$.fn.UseTooltip = function (mode) {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var c3_total = jQuery.parseJSON($('#c3_total').val());

                var x_index = getDate(x).split("/")[0];
                var per = 0;
                if (numberWithCommas(y) != 0)
                    per = (numberWithCommas(y) * 100 / c3_total[x_index]).toFixed(2);

                var color = item.series.color;
                var month = new Date(x).getMonth();
                var unit = $('#currency_unit').val();

                var tooltip = '';
                if (mode == 'budget') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong> ("+ unit +")";
                } else if (mode == 'quantity') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + "</strong>";
                } else if (mode == 'C3AC3B'){
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + " - "+ per + " % </strong>";
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

$.fn.UseTooltipByWeeks = function (mode) {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var c3_total = jQuery.parseJSON($('#c3_total').val());

                var x_index = x;
                var per = 0;
                if (numberWithCommas(y) != 0)
                    per = (numberWithCommas(y) * 100 / c3_total[x_index]).toFixed(2);

                var color = item.series.color;
                var month = new Date(x).getMonth();
                var unit = $('#currency_unit').val();

                var tooltip = '';
                if (mode == 'budget') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDateRange(x) + " : <strong>" + numberWithCommas(y) + "</strong> ("+ unit +")";
                } else if (mode == 'quantity') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDateRange(x) + " : <strong>" + numberWithCommas(y) + "</strong>";
                } else if (mode == 'C3AC3B') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDateRange(x) + " : <strong>" + numberWithCommas(y) + " - "+ per + " % </strong>";
                } else {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + getDateRange(x) + " : <strong>" + numberWithCommas(y) + "</strong> (%)";
                }

                showTooltip(item.pageX - 40, item.pageY, color, tooltip);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

$.fn.UseTooltipByMonths = function (mode) {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var c3_total = jQuery.parseJSON($('#c3_total').val());

                var x_index = x;
                var per = 0;
                if (numberWithCommas(y) != 0)
                    per = (numberWithCommas(y) * 100 / c3_total[x_index]).toFixed(2);

                var color = item.series.color;
                var month = new Date(x).getMonth();
                var unit = $('#currency_unit').val();

                var tooltip = ''
                if (mode == 'budget') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + monthNames[x - 1] + " : <strong>" + numberWithCommas(y) + "</strong> ("+ unit +")";
                } else if (mode == 'quantity') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + monthNames[x - 1] + " : <strong>" + numberWithCommas(y) + "</strong>";
                } else if (mode == 'C3AC3B') {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + monthNames[x - 1] + " : <strong>" + numberWithCommas(y) + " - "+ per + " % </strong>";
                } else {
                    tooltip = "<strong>" + item.series.label + "</strong><br>" + monthNames[x - 1] + " : <strong>" + numberWithCommas(y) + "</strong> (%)";
                }

                showTooltip(item.pageX, item.pageY, color, tooltip);
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
    var parts = number.toFixed().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function numberWithDots(number) {
    var parts = number.toFixed().split(".");
    ;
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
        filterSubReport();
    })

    $('.nav-tabs a[href="#by_days"]').on('show.bs.tab', function () {
        loadDataByDays();
    });

    $('#budget_chk input[type=checkbox]').change(function (e) {
        var month = $('input[name="budget_month"]').val();
        get_budget(month);
    })

    $('#quantity_chk input[type=checkbox]').change(function (e) {
        var month = $('input[name="quantity_month"]').val();
        get_quantity(month);
    })

    $('#quality_chk input[type=checkbox]').change(function (e) {
        var month = $('input[name="quality_month"]').val();
        get_quality(month);
    })

    $('#C3A-C3B_chk input[type=checkbox]').change(function (e) {
        var month = $('input[name="C3A-C3B_month"]').val();
        get_C3AC3B(month);
    })

    $('.nav-tabs a[href="#by_weeks"]').on('show.bs.tab', function () {
        loadDataByWeeks();
    });

    $('#budget_by_weeks_chk input[type=checkbox]').change(function (e) {
        loadDataByWeeks('budget');
    })

    $('#quantity_by_weeks_chk input[type=checkbox]').change(function (e) {
        loadDataByWeeks('quantity');
    })

    $('#quality_by_weeks_chk input[type=checkbox]').change(function (e) {
        loadDataByWeeks('quality');
    })

    $('#C3A-C3B_by_weeks_chk input[type=checkbox]').change(function (e) {
        loadDataByWeeks('C3A-C3B');
    })

    $('.nav-tabs a[href="#by_months"]').on('show.bs.tab', function () {
        loadDataByMonths();
    });

    $('#budget_by_months_chk input[type=checkbox]').change(function (e) {
        loadDataByMonths('budget');
    })

    $('#quantity_by_months_chk input[type=checkbox]').change(function (e) {
        loadDataByMonths('quantity');
    })

    $('#quality_by_months_chk input[type=checkbox]').change(function (e) {
        loadDataByMonths('quality');
    })

    $('#C3A-C3B_by_months_chk input[type=checkbox]').change(function (e) {
        loadDataByMonths('C3A-C3B');
    })
});

function filterSubReport(){
    var tab_active = $('.nav-tabs').find('li.active a');
    var href = $(tab_active).attr("href");

    if (href == '#by_days'){
        loadDataByDays();
    }else if (href == '#by_weeks'){
        loadDataByWeeks();
    }else if (href == '#by_months'){
        loadDataByMonths();
    }
}

function get_budget(month) {

    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name="budget_url"]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name="budget_tot_url"]').val();
    }

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

    var data = {};
    data.source_id = source_id;
    data.team_id = team_id;
    data.marketer_id = marketer_id;
    data.campaign_id = campaign_id;
    data.subcampaign_id = subcampaign_id;
    data.month = month;

    $.get(url, data, function (data) {
        set_budget_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function get_quantity(month) {
    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name="quantity_url"]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name="quantity_tot_url"]').val();
    }

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

    var data = {};
    data.source_id = source_id;
    data.team_id = team_id;
    data.marketer_id = marketer_id;
    data.campaign_id = campaign_id;
    data.subcampaign_id = subcampaign_id;
    data.month = month;

    $.get(url, data, function (data) {

        set_quantity_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function get_quality(month) {
    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name="quality_tot_url"]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name="quality_tot_url"]').val();
    }

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

    var data = {};
    data.source_id = source_id;
    data.team_id = team_id;
    data.marketer_id = marketer_id;
    data.campaign_id = campaign_id;
    data.subcampaign_id = subcampaign_id;
    data.month = month;

    $.get(url, data, function (data) {
        set_quality_chart(data);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function get_C3AC3B(month) {
    var url = $('input[name="C3AC3B_url"]').val();
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

    var data = {};
    data.source_id = source_id;
    data.team_id = team_id;
    data.marketer_id = marketer_id;
    data.campaign_id = campaign_id;
    data.subcampaign_id = subcampaign_id;
    data.month = month;

    $("#C3A-C3B_chart").parent().parent().parent().parent().find('.loading').css("display", "block");
    $.get(url, data, function (rs) {
        set_C3AC3B(rs, $("#C3A-C3B_chart"), $('#C3A-C3B_chk'), 'by_days');
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function set_C3AC3B(rs, element, checkbox, type) {
    var item = element;
    var dataSet = [];
    var arr_color = [];

    var C3A_Duplicated = {data: jQuery.parseJSON(rs.C3A_Duplicated), label: "C3A-Duplicated"};
    var C3B_Under18 = {data: jQuery.parseJSON(rs.C3B_Under18), label: "C3B-Under18"};
    var C3B_Duplicated15Days = {data: jQuery.parseJSON(rs.C3B_Duplicated15Days), label: "C3B-Duplicated15Days"};
    var C3A_Test = {data: jQuery.parseJSON(rs.C3A_Test), label: "C3A-Test"};
    $('#c3_total').val(rs.c3);

    var lst_checkbox = checkbox.find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'C3A-Duplicated') {
            dataSet.push(C3A_Duplicated);
            arr_color.push('#800000');
        }
        if ($label == 'C3B-Under18') {
            dataSet.push(C3B_Under18);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'C3B-Duplicated15Days') {
            dataSet.push(C3B_Duplicated15Days);
            arr_color.push('#808080')
        }
        if ($label == 'C3A-Test') {
            dataSet.push(C3A_Test);
            arr_color.push('#7CFC00')
        }
    });


    initChart(item, dataSet, arr_color, type);
    if (type == 'by_days')
        element.UseTooltip('C3AC3B');
    else if (type == 'by_weeks')
        element.UseTooltipByWeeks('C3AC3B');
    else if (type == 'by_months')
        element.UseTooltipByMonths('C3AC3B');
    element.parent().parent().parent().parent().find('.loading').css("display", "none");
}

function set_budget_chart(data) {
    $('span#me_re').text(data.me_re);

    var item = $("#budget_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};
    var data_me = {data: jQuery.parseJSON(data.me), label: "ME"};
    var data_re = {data: jQuery.parseJSON(data.re), label: "RE"};

    var lst_checkbox = $('#budget_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
        if ($label == 'ME') {
            dataSet.push(data_me);
            arr_color.push('#000')
        }
        if ($label == 'RE') {
            dataSet.push(data_re);
            arr_color.push('#008000')
        }
    });

    initChart(item, dataSet, arr_color, 'by_days');
    $("#budget_chart").UseTooltip('budget');

}

function set_quantity_chart(data) {
    var item = $("#quantity_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};

    var lst_checkbox = $('#quantity_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
    });

    initChart(item, dataSet, arr_color, 'by_days');
    $("#budget_chart").UseTooltip('quantity');

}

function set_quality_chart(data) {
    var item = $("#quality_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l3_c3b     = {data: jQuery.parseJSON(data.l3_c3b), label: "L3/C3B"};
    var data_l3_c3bg    = {data: jQuery.parseJSON(data.l3_c3bg), label: "L3/C3BG"};
    var data_l3_l1      = {data: jQuery.parseJSON(data.l3_l1), label: "L3/L1"};
    var data_l1_c3bg    = {data: jQuery.parseJSON(data.l1_c3bg), label: "L1/C3BG"};
    var data_c3bg_c3b   = {data: jQuery.parseJSON(data.c3bg_c3b), label: "C3BG/C3B"};
    var data_l6_l3      = {data: jQuery.parseJSON(data.l6_l3), label: "L6/L3"};
    var data_l8_l6      = {data: jQuery.parseJSON(data.l8_l6), label: "L8/L6"};
    var data_c3a_c3     = {data: jQuery.parseJSON(data.c3a_c3), label: "C3A/C3"};

    var lst_checkbox = $('#quality_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L3/C3B') {
            dataSet.push(data_l3_c3b);
            arr_color.push('#800000');
        }
        if ($label == 'L3/C3BG') {
            dataSet.push(data_l3_c3bg);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L3/L1') {
            dataSet.push(data_l3_l1);
            arr_color.push('#808080')
        }
        if ($label == 'L1/C3BG') {
            dataSet.push(data_l1_c3bg);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3BG/C3B') {
            dataSet.push(data_c3bg_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'L6/L3') {
            dataSet.push(data_l6_l3);
            arr_color.push('#1E90FF')
        }
        if ($label == 'L8/L6') {
            dataSet.push(data_l8_l6);
            arr_color.push('#000')
        }
        if ($label == 'C3A/C3') {
            dataSet.push(data_c3a_c3);
            arr_color.push('#CC0099')
        }
    });

    initChart(item, dataSet, arr_color, 'by_days');
    $("#quality_chart").UseTooltip('quality');
}

function getDate(date) {
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

function initChart(item, data, arr_color, type) {
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
        // xaxis : {
        //     mode: "time",
        //     timeformat: "%d/%m",
        //     ticks : 20
        // },
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
        colors: arr_color,
    };

    if (type == 'by_days') {
        option.xaxis = {
            mode: "time",
            timeformat: "%d/%m",
            ticks: 20
        };
    } else if (type == 'by_weeks') {
        $w = moment().weeksInYear();
        $arr_weeks = [];
        for ($i = 0; $i <= $w; $i++) {
            $arr_weeks[$i] = $i;
        }

        option.xaxis = {
            ticks: $arr_weeks,
        };
        option.grid= {
            hoverable: true,
            // clickable : true,
            tickColor: $chrt_border_color,
            borderWidth: 0,
            borderColor: $chrt_border_color,
            margin: {
                left: 10
            }
        }
    } else if (type == 'by_months') {
        option.xaxis = {
            ticks: [
                [1, "Jan"], [2, "Feb"], [3, "Mar"], [4, "Apr"], [5, "May"], [6, "Jun"],
                [7, "Jul"], [8, "Aug"], [9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dec"]
            ],
        };
    }

    if (item.length) {
        $.plot(item, data, option);
    }
    /* end site stats */
}

function loadDataByWeeks(type) {

    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name=get_by_weeks]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name=get_tot_by_weeks]').val();
    }

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
    var unit = $('#currency_unit').val();

    $('.loading').show();

    $.ajax({
        url: url,
        type: 'GET',
        contentType: "application/json",
        dataType: "json",
        data: {
            source_id: source_id,
            team_id: team_id,
            marketer_id: marketer_id,
            campaign_id: campaign_id,
            subcampaign_id: subcampaign_id,
            type:type,
            unit: unit
        }
    }).done(function (response) {
        if (type == 'budget') {
            set_budget_chart_by_weeks(response.budget);
        }
        else if (type == 'quantity') {
            set_quantity_chart_by_weeks(response.quantity);
        }
        else if (type == 'quality') {
            set_quality_chart_by_weeks(response.quality);
        }
        else if (type == 'C3A-C3B') {
            set_C3AC3B(response.C3AC3B, $("#C3A-C3B_by_weeks_chart"), $('#C3A-C3B_by_weeks_chk'), 'by_weeks');
        }
        else {
            set_budget_chart_by_weeks(response.budget);
            set_quantity_chart_by_weeks(response.quantity);
            set_quality_chart_by_weeks(response.quality);
            set_C3AC3B(response.C3AC3B, $("#C3A-C3B_by_weeks_chart"), $('#C3A-C3B_by_weeks_chk'), 'by_weeks');
        }
        $('.loading').hide();
    });

}

function loadDataByDays() {

    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name=get_by_days]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name=get_tot_by_days]').val();
    }

    var source_id = $('select[name="source_id"]').val();
    var team_id = $('select[name="team_id"]').val();
    var marketer_id = $('select[name="marketer_id"]').val();
    var campaign_id = $('select[name="campaign_id"]').val();
    var subcampaign_id = $('select[name="subcampaign_id"]').val();
    var registered_date = $('.registered_date').text();
    var budget_month = $('input[name="budget_month"]').val();
    var quantity_month = $('input[name="quantity_month"]').val();
    var quality_month = $('input[name="quality_month"]').val();
    var unit = $('#currency_unit').val();
    var C3AC3B_month = $('input[name="C3AC3B_month"]').val();

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
            source_id: source_id,
            team_id: team_id,
            marketer_id: marketer_id,
            campaign_id: campaign_id,
            subcampaign_id: subcampaign_id,
            budget_month: budget_month,
            quantity_month: quantity_month,
            quality_month: quality_month,
            unit: unit,
            C3AC3B_month: C3AC3B_month
        }
    }).done(function (response) {
        set_budget_chart(response.budget);
        set_quantity_chart(response.quantity);
        set_quality_chart(response.quality);
        console.log('abc');
        set_C3AC3B(response.C3AC3B, $("#C3A-C3B_by_days_chart"), $('#C3A-C3B_by_days_chk'), 'by_days');
        $('.loading').hide();
    });

}

function loadDataByMonths(type) {

    var mode = $('select[name="mode"]').val();
    if(mode == 'TOA'){
        var url = $('input[name=get_by_months]').val();
    }else if (mode == 'TOT'){
        var url = $('input[name=get_tot_by_months]').val();
    }

    var source_id = $('select[name="source_id"]').val();
    var team_id = $('select[name="team_id"]').val();
    var marketer_id = $('select[name="marketer_id"]').val();
    var campaign_id = $('select[name="campaign_id"]').val();
    var subcampaign_id = $('select[name="subcampaign_id"]').val();
    var registered_date = $('.registered_date').text();
    var unit = $('#currency_unit').val();

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
            source_id: source_id,
            team_id: team_id,
            marketer_id: marketer_id,
            campaign_id: campaign_id,
            subcampaign_id: subcampaign_id,
            type:type,
            unit: unit
        }
    }).done(function (response) {
        if (type == 'budget') {
            set_budget_chart_by_months(response.budget);
        }
        else if (type == 'quantity') {
            set_quantity_chart_by_months(response.quantity);
        }
        else if (type == 'quality') {
            set_quality_chart_by_months(response.quality);
        }
        else if (type == 'C3A-C3B') {
            set_C3AC3B(response.C3AC3B, $("#C3A-C3B_by_months_chart"), $('#C3A-C3B_by_months_chk'), 'by_months');
        }
        else {
            set_budget_chart_by_months(response.budget);
            set_quantity_chart_by_months(response.quantity);
            set_quality_chart_by_months(response.quality);
            set_C3AC3B(response.C3AC3B, $("#C3A-C3B_by_months_chart"), $('#C3A-C3B_by_months_chk'), 'by_months');
        }
        $('.loading').hide();
    });
}

function set_budget_chart_by_weeks(data) {
    $('span#me_re_by_weeks').text(data.me_re);

    var item = $("#budget_by_weeks_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};
    var data_me = {data: jQuery.parseJSON(data.me), label: "ME"};
    var data_re = {data: jQuery.parseJSON(data.re), label: "RE"};

    var lst_checkbox = $('#budget_by_weeks_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
        if ($label == 'ME') {
            dataSet.push(data_me);
            arr_color.push('#000')
        }
        if ($label == 'RE') {
            dataSet.push(data_re);
            arr_color.push('#008000')
        }
    });

    initChart(item, dataSet, arr_color, 'by_weeks');
    $("#budget_by_weeks_chart").UseTooltipByWeeks('budget');

}

function set_quantity_chart_by_weeks(data) {

    var item = $("#quantity_by_weeks_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};

    var lst_checkbox = $('#quantity_by_weeks_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
    });

    initChart(item, dataSet, arr_color, 'by_weeks');
    $("#quantity_by_weeks_chart").UseTooltipByWeeks('quantity');

}

function set_quality_chart_by_weeks(data) {
    var item = $("#quality_by_weeks_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l3_c3b     = {data: jQuery.parseJSON(data.l3_c3b), label: "L3/C3B"};
    var data_l3_c3bg    = {data: jQuery.parseJSON(data.l3_c3bg), label: "L3/C3BG"};
    var data_l3_l1      = {data: jQuery.parseJSON(data.l3_l1), label: "L3/L1"};
    var data_l1_c3bg    = {data: jQuery.parseJSON(data.l1_c3bg), label: "L1/C3BG"};
    var data_c3bg_c3b   = {data: jQuery.parseJSON(data.c3bg_c3b), label: "C3BG/C3B"};
    var data_l6_l3      = {data: jQuery.parseJSON(data.l6_l3), label: "L6/L3"};
    var data_l8_l6      = {data: jQuery.parseJSON(data.l8_l6), label: "L8/L6"};
    var data_c3a_c3     = {data: jQuery.parseJSON(data.c3a_c3), label: "C3A/C3"};

    var lst_checkbox = $('#quality_by_weeks_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L3/C3B') {
            dataSet.push(data_l3_c3b);
            arr_color.push('#800000');
        }
        if ($label == 'L3/C3BG') {
            dataSet.push(data_l3_c3bg);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L3/L1') {
            dataSet.push(data_l3_l1);
            arr_color.push('#808080')
        }
        if ($label == 'L1/C3BG') {
            dataSet.push(data_l1_c3bg);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3BG/C3B') {
            dataSet.push(data_c3bg_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'L6/L3') {
            dataSet.push(data_l6_l3);
            arr_color.push('#1E90FF')
        }
        if ($label == 'L8/L6') {
            dataSet.push(data_l8_l6);
            arr_color.push('#000')
        }
        if ($label == 'C3A/C3') {
            dataSet.push(data_c3a_c3);
            arr_color.push('#CC0099')
        }
    });

    initChart(item, dataSet, arr_color, 'by_weeks');
    $("#quality_by_weeks_chart").UseTooltipByWeeks('quality');
}

function getDateRange(weekNumber) {
    var beginningOfWeek = moment().week(weekNumber).startOf('week');
    var endOfWeek = moment().week(weekNumber).startOf('week').add(6, 'days');

    if(weekNumber == 1){
        return '01/01' + '-' + endOfWeek.format('DD/MM');
    }

    if (weekNumber == 52) {
        return beginningOfWeek.format('DD/MM') + '-' + '31/12';
    }

    return beginningOfWeek.format('DD/MM') + '-' + endOfWeek.format('DD/MM');
}

function set_budget_chart_by_months(data) {
    $('span#me_re_by_months').text(data.me_re);

    var item = $("#budget_by_months_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};
    var data_me = {data: jQuery.parseJSON(data.me), label: "ME"};
    var data_re = {data: jQuery.parseJSON(data.re), label: "RE"};

    var lst_checkbox = $('#budget_by_months_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
        if ($label == 'ME') {
            dataSet.push(data_me);
            arr_color.push('#000')
        }
        if ($label == 'RE') {
            dataSet.push(data_re);
            arr_color.push('#008000')
        }
    });

    initChart(item, dataSet, arr_color, 'by_months');
    $("#budget_by_months_chart").UseTooltipByMonths('budget');

}

function set_quantity_chart_by_months(data) {

    var item = $("#quantity_by_months_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l1 = {data: jQuery.parseJSON(data.l1), label: "L1"};
    var data_l3 = {data: jQuery.parseJSON(data.l3), label: "L3"};
    var data_l6 = {data: jQuery.parseJSON(data.l6), label: "L6"};
    var data_l8 = {data: jQuery.parseJSON(data.l8), label: "L8"};
    var data_c3b = {data: jQuery.parseJSON(data.c3b), label: "C3B"};
    var data_c3bg = {data: jQuery.parseJSON(data.c3bg), label: "C3BG"};

    var lst_checkbox = $('#quantity_by_months_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L1') {
            dataSet.push(data_l1);
            arr_color.push('#800000');
        }
        if ($label == 'L3') {
            dataSet.push(data_l3);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L6') {
            dataSet.push(data_l6);
            arr_color.push('#808080')
        }
        if ($label == 'L8') {
            dataSet.push(data_l8);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3B') {
            dataSet.push(data_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'C3BG') {
            dataSet.push(data_c3bg);
            arr_color.push('#1E90FF')
        }
    });

    initChart(item, dataSet, arr_color, 'by_months');
    $("#quantity_by_months_chart").UseTooltipByMonths('quantity');

}

function set_quality_chart_by_months(data) {
    var item = $("#quality_by_months_chart");
    var dataSet = [];
    var arr_color = [];

    var data_l3_c3b     = {data: jQuery.parseJSON(data.l3_c3b), label: "L3/C3B"};
    var data_l3_c3bg    = {data: jQuery.parseJSON(data.l3_c3bg), label: "L3/C3BG"};
    var data_l3_l1      = {data: jQuery.parseJSON(data.l3_l1), label: "L3/L1"};
    var data_l1_c3bg    = {data: jQuery.parseJSON(data.l1_c3bg), label: "L1/C3BG"};
    var data_c3bg_c3b   = {data: jQuery.parseJSON(data.c3bg_c3b), label: "C3BG/C3B"};
    var data_l6_l3      = {data: jQuery.parseJSON(data.l6_l3), label: "L6/L3"};
    var data_l8_l6      = {data: jQuery.parseJSON(data.l8_l6), label: "L8/L6"};
    var data_c3a_c3     = {data: jQuery.parseJSON(data.c3a_c3), label: "C3A/C3"};

    var lst_checkbox = $('#quality_by_months_chk').find('input[type=checkbox]:checked');
    jQuery.each(lst_checkbox, function (index, checkbox) {
        $label = $(checkbox).val();
        if ($label == 'L3/C3B') {
            dataSet.push(data_l3_c3b);
            arr_color.push('#800000');
        }
        if ($label == 'L3/C3BG') {
            dataSet.push(data_l3_c3bg);
            arr_color.push('#6A5ACD')
        }
        if ($label == 'L3/L1') {
            dataSet.push(data_l3_l1);
            arr_color.push('#808080')
        }
        if ($label == 'L1/C3BG') {
            dataSet.push(data_l1_c3bg);
            arr_color.push('#7CFC00')
        }
        if ($label == 'C3BG/C3B') {
            dataSet.push(data_c3bg_c3b);
            arr_color.push('#FF8C00')
        }
        if ($label == 'L6/L3') {
            dataSet.push(data_l6_l3);
            arr_color.push('#1E90FF')
        }
        if ($label == 'L8/L6') {
            dataSet.push(data_l8_l6);
            arr_color.push('#000')
        }
        if ($label == 'C3A/C3') {
            dataSet.push(data_c3a_c3);
            arr_color.push('#CC0099')
        }
    });

    initChart(item, dataSet, arr_color, 'by_months');
    $("#quality_by_months_chart").UseTooltipByMonths('quality');
}



