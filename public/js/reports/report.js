$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});

var __row0 = $('#table_report tr#ad-total');

$(document).ready(function () {

    var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
    }

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

    /* BASIC ;*/
    var responsiveHelper_table_report = undefined;

    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };

    $('#table_report').dataTable({
        "sDom":
        "<'tb-only't>" +
        "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
        "autoWidth": true,
        "preDrawCallback": function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_table_report) {
                responsiveHelper_table_report = new ResponsiveDatatablesHelper($('#table_report'), breakpointDefinition);
            }
        },
        "rowCallback": function (nRow) {
            responsiveHelper_table_report.createExpandIcon(nRow);
        },
        "drawCallback": function (oSettings) {
            responsiveHelper_table_report.respond();
            fixedTotalRow();
        },
        "order": [],
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            if(iTotal - 1 == 0){
                return "";
            }
            iStart = iStart - 1;
            if(iStart == 0){
                iStart = 1;
            }
            iEnd = iEnd - 1;
            iTotal = iTotal - 1;
            return "Showing " + iStart + " to " + iEnd + " of " + iTotal + " entries";
        },
        'scrollY'       : '55vh',
        "scrollX"       : true,
        'scrollCollapse': true,
    });


    /* END BASIC */
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

    $('#subcampaign_id').change(function (e) {
        var url = $('#subcampaign_id').attr('data-url');
        var subcampaign_id = $('select[name="subcampaign_id"]').val();
        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                subcampaign_id: subcampaign_id
            }
        }).done(function (response) {
            $('#landing_page').html(response.content_landingpage);
            $("#landing_page").select2();
        });
    })
});

$(document).ready(function () {
    $('#search-form-report').submit(function (e) {
        e.preventDefault();
        var url = $('#search-form-report').attr('url');
        var source_id = $('select[name="source_id"]').val();
        var team_id = $('select[name="team_id"]').val();
        var marketer_id = $('select[name="marketer_id"]').val();
        var campaign_id = $('select[name="campaign_id"]').val();
        var subcampaign_id = $('select[name="subcampaign_id"]').val();
        var registered_date = $('.registered_date').text();
        var landing_page    = $('select[name="landing_page"]').val();

        $('input[name="source_id"]').val(source_id);
        $('input[name="team_id"]').val(team_id);
        $('input[name="marketer_id"]').val(marketer_id);
        $('input[name="campaign_id"]').val(campaign_id);
        $('input[name="subcampaign_id"]').val(subcampaign_id);
        $('input[name="registered_date"]').val(registered_date);

        // var url = "{!! route('contacts.filter') !!}";
        $('.loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                source_id       : source_id,
                team_id         : team_id,
                marketer_id     : marketer_id,
                campaign_id     : campaign_id,
                subcampaign_id  : subcampaign_id,
                registered_date : registered_date,
                landing_page    : landing_page
            }
        }).done(function (response) {
            $('.loading').hide();
            $('.wrapper_report').html(response);

            var responsiveHelper_table_report = undefined;
            var page_size   = $('input[name="page_size"]').val();

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };
            $('#table_report').dataTable({
                "sDom":
                "<'tb-only't>" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_table_report) {
                        responsiveHelper_table_report = new ResponsiveDatatablesHelper($('#table_report'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_table_report.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_table_report.respond();

                    fixedTotalRow();
                },
                'order': [],
                "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    if(iTotal - 1 == 0){
                        return "";
                    }
                    iStart = iStart - 1;
                    if(iStart == 0){
                        iStart = 1;
                    }
                    iEnd = iEnd - 1;
                    iTotal = iTotal - 1;
                    return "Showing " + iStart + " to " + iEnd + " of " + iTotal + " entries";
                },
                "iDisplayLength": page_size,
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
            });
        });
    });
});

function fixedTotalRow () {

    if ($('#table_report tbody tr').length != 1){
        if($('#table_report tr#ad-total').length == 1){
            __row0 = $('#table_report tr#ad-total').remove();
        }

        $('#table_report tbody tr:first').before(__row0);
    }
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