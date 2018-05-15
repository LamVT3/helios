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

    function c3range_span(start, end) {
        $('#c3range span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
    }

    c3range_span(start, end);

    $('#c3range').daterangepicker({
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
    }, c3range_span);
});

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
    $('select#limit').change(function (e) {
        var limit = $('select#limit').val();
        $('input[name="limit"]').val(limit);
    })
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var registered_date = $('.registered_date').text();
        $('input[name="registered_date"]').val(registered_date);
    });
});

$(document).ready(function () {

    $('#search-form-c3').submit(function (e) {
        e.preventDefault();
        countExported();

        $('.loading').show();
        setTimeout(function(){
            // initDataTable();
            $('.loading').hide();
        }, 1000);
    });

    $('button#export').click(function (e) {
        e.preventDefault();
        $('#export-form-c3').submit();
        countExported();
        setTimeout(function(){
            countExported();
        }, 2000);

    });

});

function initDataTable() {

    var url             = $('#search-form-c3').attr('url');
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var clevel          = $('select[name="clevel"]').val();
    var current_level   = $('select[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var c3bg_checkbox   = $('input[name="c3bg"]').val();
    var checked_date    = $('.checked_date').text();
    var page_size       = $('input[name="page_size"]').val();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var is_export       = $('select[name="is_export"]').val();
    var limit           = $('select[name="limit"]').val();

    $('input[name="source_id"]').val(source_id);
    $('input[name="team_id"]').val(team_id);
    $('input[name="marketer_id"]').val(marketer_id);
    $('input[name="campaign_id"]').val(campaign_id);
    $('input[name="subcampaign_id"]').val(subcampaign_id);
    $('input[name="clevel"]').val(clevel);
    $('input[name="current_level"]').val(current_level);
    $('input[name="registered_date"]').val(registered_date);
    $('input[name="checked_date"]').val(checked_date);
    $('input[name="c3bg"]').val(c3bg_checkbox);
    $('input[name="is_export"]').val(is_export);
    $('input[name="limit"]').val(limit);

    /* BASIC ;*/
    var responsiveHelper_table_campaign = undefined;

    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };

    $('#table_contacts').dataTable({
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
        "<'tb-only't>" +
        "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
        "autoWidth": true,
        "preDrawCallback": function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_table_campaign) {
                responsiveHelper_table_campaign = new ResponsiveDatatablesHelper($('#table_contacts'), breakpointDefinition);
            }
        },
        "rowCallback": function (nRow) {
            responsiveHelper_table_campaign.createExpandIcon(nRow);
        },
        "drawCallback": function (oSettings) {
            responsiveHelper_table_campaign.respond();
        },
        "order": [],
        "destroy": true,
        "iDisplayLength": page_size,
        // "processing": true,
        "serverSide": true,
        "ajax": {
            url: url,
            type: "GET",
            data: function (d) {
                    d.source_id         = source_id,
                    d.team_id           = team_id,
                    d.marketer_id       = marketer_id,
                    d.campaign_id       = campaign_id,
                    d.clevel            = clevel,
                    d.current_level     = current_level,
                    d.subcampaign_id    = subcampaign_id,
                    d.is_export         = is_export,
                    d.registered_date   = registered_date,
                    d.checked_date      = checked_date,
                    d.c3bg_checkbox     = c3bg_checkbox,
                    d.limit             = limit
            }
        },
        "columns": [
            {
                "data" : 'name',
                "render": function ( data, type, row, meta ) {
                    return '<a href="javascript:void(0)" class="name name_link" data-id="' + data[0] + '">' + data[1] + '</a>';
                }
            },
            { "data" : 'email'},
            { "data" : 'phone'},
            { "data" : 'age'},
            { "data" : 'submit_time'},
            { "data" : 'clevel'},
            { "data" : 'current_level',     "defaultContent": "-"},
            { "data" : 'source_name',       "defaultContent": "-"},
            { "data" : 'team_name',         "defaultContent": "-"},
            { "data" : 'marketer_name',     "defaultContent": "-"},
            { "data" : 'campaign_name',     "defaultContent": "-"},
            { "data" : 'subcampaign_name',  "defaultContent": "-"},
            { "data" : 'ad_name',           "defaultContent": "-"},
            { "data" : 'landing_page',      "defaultContent": "-"},
            {
                "data" : 'name',
                "render": function ( data, type, row, meta ) {
                    return '<a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="'+ data[0] +'">' +
                        '<i class="fa fa-eye"></i></a>';
                    // + '<a data-toggle="modal" class="btn btn-xs btn-default"' +
                    // 'data-target="#deleteModal data-item-id="'+ data[0] +'data-item-name="'+ data[1] +'"' +
                    // 'data-original-title="Delete Row"><i class="fa fa-times"></i></a>';
                }
            },
        ],
        'scrollY'       : '55vh',
        "scrollX"       : true,
        'scrollCollapse': true,
        "createdRow": function ( row, data, index ) {
            if(data['is_export']){
                $(row).addClass('is_export');
            }
        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            if(iTotal == 0){
                return "";
            }
            var exported    = $('input[name="exported"]').val();
            var count_str   = '<span class="text-success">' + ' (' + exported + ' exported' + ')' + '</span>';

            return "Showing " + iStart + " to " + iEnd + " of " + iTotal + " entries" + count_str;
        },
    });
}

$(document).ready(function () {
    $('a#filter').click(function (e) {
        e.preventDefault();

        if( $('a#filter i').hasClass('fa-angle-down')){
            $('a#filter i').removeClass('fa-angle-down');
            $('a#filter i').addClass('fa-angle-up');
            $('div#filter').show(500);
        }
        else{
            $('a#filter i').removeClass('fa-angle-up');
            $('a#filter i').addClass('fa-angle-down');
            $('div#filter').hide(500);
        }
    });

});

function countExported() {
    var status = $('select[name="is_export"]').val();


    var url             = $('input[name="exported_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var clevel          = $('select[name="clevel"]').val();
    var current_level   = $('select[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();

    var data = {};
    data.source_id         = source_id;
    data.team_id           = team_id;
    data.marketer_id       = marketer_id;
    data.campaign_id       = campaign_id;
    data.clevel            = clevel;
    data.current_level     = current_level;
    data.subcampaign_id    = subcampaign_id;
    data.registered_date   = registered_date;

    $.get(url, data, function (data) {
        setTimeout(function(){
            $('input[name="exported"]').val(data);
            if(status == '0'){
                $('input[name="exported"]').val(0);
            }
            initDataTable();
        }, 2000);

    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}
