$(".select2").select2({
    placeholder: "Select a State",
    allowClear: true
});
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
        "iDisplayLength": 50
    });


//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

    /* END BASIC */
});
$(document).ready(function () {
    $('#source_id').change(function (e) {
        var url = $('#source_id').attr('data-url');
        var source_id = $('select[name="source_id"]').val();
        if (source_id == 'all') {
            source_id = '';
        } else {
            source_id = source_id;
        }
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
            $('#campaign_id').html(response.content_campaign);
            $("#campaign_id").select2();
        });
    })
});
$(document).ready(function () {
    $('#team_id').change(function (e) {
        var url = $('#team_id').attr('data-url');
        var team_id = $('select[name="team_id"]').val();
        if (team_id == 'all') {
            team_id = '';
        } else {
            team_id = team_id;
        }
        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                team_id: team_id
            }
        }).done(function (response) {
            if (team_id) {
                $('#source_id').html(response.content_source);
                $("#source_id").select2();
                $('#campaign_id').html(response.content_campaign);
                $("#campaign_id").select2();
            }
        });
    })
});
$(document).ready(function () {
    $('#campaign_id').change(function (e) {
        var url = $('#campaign_id').attr('data-url');
        var campaign_id = $('select[name="campaign_id"]').val();
        if (campaign_id == 'all') {
            campaign_id = '';
        } else {
            campaign_id = campaign_id;
        }
        $.ajax({
            url: url,
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            data: {
                campaign_id: campaign_id
            }
        }).done(function (response) {
            if (campaign_id) {
                $('#source_id').html(response.content_source);
                $("#source_id").select2();
                $('#team_id').html(response.content_team);
                $("#team_id").select2();
            }
        });
    })
});

$(document).ready(function () {
    $('#search-form-c3').submit(function (e) {
        e.preventDefault();
        var group = 'all';
        var url = $('#search-form-c3').attr('url');
        var source_id = $('select[name="source_id"]').val();
        var team_id = $('select[name="team_id"]').val();
        var marketer_id = $('select[name="marketer_id"]').val();
        var campaign_id = $('select[name="campaign_id"]').val();
        var current_level = $('select[name="current_level"]').val();
        var registered_date = $('.registered_date').text();
        if (source_id == group) {
            source_id = '';
        }
        else {
            source_id = source_id;
        }
        if (team_id == group) {
            team_id = '';
        }
        else {
            team_id = team_id;
        }
        if (marketer_id == group) {
            marketer_id = '';
        } else {
            marketer_id = marketer_id;
        }

        if (campaign_id == group) {
            campaign_id = '';
        }
        else {
            campaign_id = campaign_id;
        }
        if (current_level == group) {
            current_level = '';
        }
        else {
            current_level = current_level;
        }
        $('input[name="source_id"]').val(source_id);
        $('input[name="team_id"]').val(team_id);
        $('input[name="marketer_id"]').val(marketer_id);
        $('input[name="campaign_id"]').val(campaign_id);
        $('input[name="current_level"]').val(current_level);
        $('input[name="registered_date"]').val(registered_date);
        // var url = "{!! route('contacts.filter') !!}";
        $('.loading').show();
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
            $('.loading').hide();
            $('.wrapper').html(response);
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
                "iDisplayLength": 50
            });
        });
    });
});

