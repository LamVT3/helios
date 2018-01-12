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
            "This Week": [moment().startOf("week"), moment().endOf("week")],
            "Last Week": [moment().subtract(1, "week").startOf("week"), moment().subtract(1, "week").endOf("week")],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
});

$(document).ready(function () {

    /* BASIC ;*/
    var responsiveHelper_table_campaign = undefined;

    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };

    $('#table_campaigns').dataTable({
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
        "t" +
        "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
        "autoWidth": true,
        "preDrawCallback": function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_table_campaign) {
                responsiveHelper_table_campaign = new ResponsiveDatatablesHelper($('#table_campaigns'), breakpointDefinition);
            }
        },
        "rowCallback": function (nRow) {
            responsiveHelper_table_campaign.createExpandIcon(nRow);
        },
        "drawCallback": function (oSettings) {
            responsiveHelper_table_campaign.respond();
        },
        "order": [[0, "desc"]]
    });


//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

    /* END BASIC */
});

$(document).ready(function () {
    $('#search-form-c3').submit(function (e) {
        e.preventDefault();
        var url = $('#search-form-c3').attr('url');
        var source_id = $('select[name="source_id"]').val();
        var team_id = $('select[name="team_id"]').val();
        var marketer_id = $('select[name="marketer_id"]').val();
        var campaign_id = $('select[name="campaign_id"]').val();
        var current_level = $('select[name="current_level"]').val();
        var registered_date = $('.registered_date').text();

        $('input[name="source_id"]').val(source_id);
        $('input[name="team_id"]').val(team_id);
        $('input[name="marketer_id"]').val(marketer_id);
        $('input[name="campaign_id"]').val(campaign_id);
        $('input[name="current_level"]').val(current_level);
        $('input[name="registered_date"]').val(registered_date);
        // var url = "{!! route('contacts.filter') !!}";
        $('#modal_gif').modal('show');
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                source_id: source_id,
                team_id: team_id,
                marketer_id: marketer_id,
                campaign_id: campaign_id,
                current_level: current_level,
                registered_date: registered_date
            }
        }).done(function (response) {
            $('#modal_gif').modal('hide');
            $('.wrapper').html(response);
        });
    });
})
