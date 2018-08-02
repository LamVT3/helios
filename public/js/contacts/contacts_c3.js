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
            $('#landing_page').html(response.content_landingpage);
            $("#landing_page").select2();
            $('#channel_id').html(response.content_channel);
            $("#channel_id").select2();
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
            $('#landing_page').html(response.content_landingpage);
            $("#landing_page").select2();
            $('#channel_id').html(response.content_channel);
            $("#channel_id").select2();
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
            $('#landing_page').html(response.content_landingpage);
            $("#landing_page").select2();
            $('#channel_id').html(response.content_channel);
            $("#channel_id").select2();
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
            $('#landing_page').html(response.content_landingpage);
            $("#landing_page").select2();
            $('#channel_id').html(response.content_channel);
            $("#channel_id").select2();
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
            $('#channel_id').html(response.content_channel);
            $("#channel_id").select2();
        });
    })

    $('input#limit').change(function (e) {
        var limit = $('input#limit').val();
        if(limit < 0){
            $('input[name="limit"]').val('');
        }else{
            $('input[name="limit"]').val(limit);
        }
    })

    $('select#is_export').change(function (e) {
        var status = $('select#is_export').val();
        $('input[id="status"]').val(status);
    })

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var registered_date = $('.registered_date').text();
        $('input[name="registered_date"]').val(registered_date);
    });

    $('input#mark_exported').change(function (e) {
        if ($('input#mark_exported').is(":checked"))
        {
            $('input[name="mark_exported"]').val(1);
        }
        else
        {
            $('input[name="mark_exported"]').val(0);
        }
    });

    $('input#mode').change(function (e) {
        $('.loading').show();

        countExported();

        setTimeout(function(){
            // initDataTable();
            // countExported();
            initDataTable();
            $('.loading').hide();
        },1000);
    });

});

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

    // $('div.alert-success').hide();

    $('#search-form-c3').submit(function (e) {
        e.preventDefault();
        $('.loading').show();

        countExported();

        setTimeout(function(){
            // initDataTable();
            // countExported();
            initDataTable();
            $('.loading').hide();
        },1000);
    });

    $('button#confirm_export').click(function (e) {
        e.preventDefault();
        var id = '';
        $("input:checkbox[id=is_update]:checked").each(function () {
            id += $(this).val() + ',';
        });

        $('input[name=contact_id]').val(id);
        console.log($('input[name=contact_id]').val());

        $('#export-form-c3').submit();

        setTimeout(function(){
            countExported();
            initDataTable();
            $('div#export_success').show();
            $('div#update_success').hide();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all
        }, 2000);
    });

    $('button#confirm_export_to_olm').click(function (e) {
        e.preventDefault();
        $('.loading').show();
        var is_update_all   = $('input[id=update_all]').is(':checked');
        var id      = {};

        if(is_update_all){
            id = 'All';
        }
        else{
            $("input:checkbox[id=is_update]:checked").each(function () {
                $statusCell = $(this).parent().siblings('td.status');
                id[$(this).val()] = $(this).val();
            });
        }

        exportToOLM(id);
        // updateStatusExport(id);
    });

    $('button#import_contact').click(function (e) {
        e.preventDefault();
        $('div#import_success').hide();
        $('div#loader').show();

        var form = $('#form-import-contact')[0];
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: $('#form-import-contact').attr('action'),
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(result){
                document.getElementById("import_text").innerHTML = result + ' Contact(s) have been imported successfully.';
                $('div#loader').hide();
                $('div#import_success').show();
                initDataTable();
                $('input#update_all').prop('checked', false); // Unchecks checkbox all
            }
        });

    });

    $('button#import_egentic').click(function (e) {
        e.preventDefault();
        $('div#import_success').hide();
        $('div#loader').show();

        var form = $('#form-import-egentic')[0];
        var data = new FormData(form);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: $('#form-import-egentic').attr('action'),
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(result){
                document.getElementById("import_text").innerHTML = result + ' Contact(s) have been imported successfully.';
                $('div#loader').hide();
                $('div#import_success').show();
                initDataTable();
                $('input#update_all').prop('checked', false); // Unchecks checkbox all
            }
        });

    });

    $('button#update_contact').click(function (e) {
        e.preventDefault();
        $('.loading').show();
        var is_update_all   = $('input[id=update_all]').is(':checked');
        var new_status      = $('input[name=status_update_all]').val();
        // if(is_update_all){
        //     updateStatusExport('', new_status);
        // }else{
        var id      = {};
        $("input:checkbox[id=is_update]:checked").each(function () {

            $statusCell = $(this).parent().siblings('td.status');
            var is_export = $($statusCell).find('select#status_update').val();
            id[$(this).val()] = is_export;
        });
        updateStatusExport(id);
        // }
    });

    $('input[id=update_all]').click(function () {
        var is_checked = this.checked;
        if(is_checked){
            $('input[name="update_all"]').val(1);
            $('button#edit_contact').hide();
            $('button#update_contact').show();
        }else{
            $('input[name="update_all"]').val(0);
            $('button#edit_contact').show();
            $('button#update_contact').hide();
        }
        $('input:checkbox[id=is_update]').prop('checked', this.checked);
        enable_update();
        $("input:checkbox[id=is_update]").each(function () {
            edit(this, 'all');
        });

    });

    $('button#edit_contact').click(function () {
        $(this).hide();
        $('button#update_contact').show();
        $("input:checkbox[id=is_update]").each(function () {
            edit(this);
        });
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
    var checked_date    = $('.checked_date').text();
    var c3bg_checkbox   = $('input[name="c3bg"]').prop('checked');
    var page_size       = $('input[name="page_size"]').val();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var is_export       = $('select[name="is_export"]').val();
    var limit           = $('input#limit').val();
    var landing_page    = $('select[name="landing_page"]').val();
    var search          = $('input[name="search_text"]').val();
    var channel         = $('select[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();


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
    $('input[name="landing_page"]').val(landing_page);
    $('input[name="channel"]').val(channel);
    $('input[name="olm_status"]').val(olm_status);

    /* BASIC ;*/
    var responsiveHelper_table_campaign = undefined;

    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };

    $('#table_contacts').dataTable({
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6'C>r>" +
        "<'tb-only't>" +
        "<'dt-toolbar-footer'<'col-sm-6 col-xs-12'i><'col-sm-6 col-xs-12'p>>",
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

            $('input[type=search]').change(function (e) {
                var search = $(this).val();
                $('input[name=search_text]').val(search);
                countExported();
            });

            $('#table_contacts input[type=checkbox]').click(function () {
                $('button#edit_contact').prop('disabled', false);
                $('button#edit_contact').removeClass('disabled');
            });

            enable_update();

            var exported        = $('input[name="exported"]').val();
            var cnt_exported    = '<strong>'+ exported + ' contact(s) export to excel'+'</strong>';
            $('p#cnt_exported').html(cnt_exported);

            var exported_olm            = $('input[name="export_to_olm"]').val();
            var cnt_exported_to_olm     = '<strong>'+ exported_olm + ' contact(s) export to OLM'+'</strong>';
            $('p#cnt_export_to_olm').html(cnt_exported_to_olm);

            responsiveHelper_table_campaign.respond();
        },
        "order": [],
        "destroy": true,
        "iDisplayLength": parseInt(page_size),
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
                    d.limit             = limit,
                    d.landing_page      = landing_page,
                    d.channel           = channel,
                    d.search_text       = search,
                    d.olm_status    = olm_status
            }
        },
        "columns": [
            {
                "data" : 'name',
                "render": function ( data, type, row, meta ) {
                    return '<input type="checkbox" class="is_update" id="is_update" onclick="edit(this);enable_update();" value="' + data[0] + '"/>';
                },
                "orderable": false,
            },
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
            { "data" : 'ad_name',           "defaultContent": "-"},
            { "data" : 'channel_name',      "defaultContent": "-",  'className' : "channel"},
            { "data" : 'source_name',       "defaultContent": "-"},
            { "data" : 'team_name',         "defaultContent": "-"},
            { "data" : 'marketer_name',     "defaultContent": "-"},
            { "data" : 'campaign_name',     "defaultContent": "-"},
            { "data" : 'subcampaign_name',  "defaultContent": "-"},
            { "data" : 'landing_page',      "defaultContent": "-"},
            { "data" : 'invalid_reason',    "defaultContent": "-"},
            // {
            //     "data" : 'name',
            //     "render": function ( data, type, row, meta ) {
            //         return '<a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="'+ data[0] +'">' +
            //             '<i class="fa fa-eye"></i></a>';
            //     }
            // },
            {
                "data" : 'name',
                "render": function ( data, type, row, meta ) {
                    return '<a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="' + data[0] + '">' +
                        '<i class="fa fa-eye"></i><b style="margin-left: 5px;">' + data[2] + '</b></a>';
                    // + '<a data-toggle="modal" class="btn btn-xs btn-default"' +
                    // 'data-target="#deleteModal data-item-id="'+ data[0] +'data-item-name="'+ data[1] +'"' +
                    // 'data-original-title="Delete Row"><i class="fa fa-times"></i></a>';
                }
            },
            {
                "data"      : 'is_export',
                'className' : "status",
                "render"    : function ( data, type, row, meta ) {
                    if(data == 1){
                        return '<span id="status">Exported</span><input type="hidden" id="old_status" value="1">';
                    }
                    return '<span id="status" >Not Exported</span><input type="hidden" id="old_status" value="0">';
                }
            },
            {
                "data"      : 'olm_status',
                'className' : "olm_status",
                "render"    : function ( data, type, row, meta ) {
                    var status = '';

                    if(data == 0){
                        status = '<span id="olm_status">Success</span><input type="hidden" id="old_status_olm" value="0">';
                    }
                    else if(data == 1){
                        status = '<span id="olm_status">Duplicated</span><input type="hidden" id="old_status_olm" value="1">';
                    }
                    else if(data == 2 || data == 3){
                        status = '<span id="olm_status">Error</span><input type="hidden" id="old_status_olm" value="2">';
                    }
                    else{
                        status = '<span id="olm_status">Not Exported</span><input type="hidden" id="old_status_olm" value="3">';
                    }
                    return status;
                }
            },
        ],
        'scrollY'       : '55vh',
        "scrollX"       : true,
        'scrollCollapse': true,
        "createdRow": function ( row, data, index ) {
            var mode = $('input[name=mode]:checked').val();
            if(mode == '0'){
                if(data['is_export'] == 1){
                    $(row).addClass('is_export');
                }
            }
            else{
                if(data['olm_status'] == 0){
                    $(row).addClass('olm_status_success');
                }
                else if(data['olm_status'] == 1){
                    $(row).addClass('olm_status_duplicated');
                }
                else if(data['olm_status'] == 2 || data['olm_status'] == 3){
                    $(row).addClass('olm_status_error');
                }
            }
        },
        // "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            // if(iTotal == 0){
            //     return "";
            // }
            // // countExportedWhenSearch();
            // var exported    = $('input[name="exported"]').val();
            // var count_str   = '<span id="cnt_exported" class="text-success">' + ' (' + exported + ' exported' + ')' + '</span>';
            //
            // return sPre + count_str;
        // },
    });

}

function countExported() {
    var status          = $('select[name="is_export"]').val();

    var url             = $('input[name="exported_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var clevel          = $('select[name="clevel"]').val();
    var current_level   = $('select[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var landing_page    = $('select[name="landing_page"]').val();
    var is_export       = $('select[name="is_export"]').val();
    var search          = $('input[type="search"]').val();
    var channel         = $('select[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();

    if(is_export === '0'){
        $('input[name="exported"]').val(0);
        // $('span#cnt_exported').text('(0 exported)');
        // initDataTable();
        return;
    }

    var data = {};
    data.source_id          = source_id;
    data.team_id            = team_id;
    data.marketer_id        = marketer_id;
    data.campaign_id        = campaign_id;
    data.clevel             = clevel;
    data.current_level      = current_level;
    data.subcampaign_id     = subcampaign_id;
    data.registered_date    = registered_date;
    data.landing_page       = landing_page;
    data.is_export          = is_export;
    data.search_text        = search;
    data.channel            = channel;
    data.olm_status         = olm_status;

    $.get(url, data, function (data) {
        console.log(data);
        $('input[name="exported"]').val(data.to_excel);
        $('input[name="export_to_olm"]').val(data.to_olm);
        // setTimeout(function(){
            // if(status == '0'){
            //     $('input[name="exported"]').val(0);
            // }
            // $('input[name="exported"]').val(data);
            // $('span#cnt_exported').text('(' + data +' exported)');
            // initDataTable();
        // }, 1000);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function countExportedWhenSearch() {
    var status          = $('select[name="is_export"]').val();

    var url             = $('input[name="exported_url"]').val();
    var source_id       = $('select[name="source_id"]').val();
    var team_id         = $('select[name="team_id"]').val();
    var marketer_id     = $('select[name="marketer_id"]').val();
    var campaign_id     = $('select[name="campaign_id"]').val();
    var clevel          = $('select[name="clevel"]').val();
    var current_level   = $('select[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var subcampaign_id  = $('select[name="subcampaign_id"]').val();
    var landing_page    = $('select[name="landing_page"]').val();
    var is_export       = $('select[name="is_export"]').val();
    var search          = $('input[type="search"]').val();
    var channel         = $('select[name="channel_id"]').val();

    if(is_export === '0'){
        $('input[name="exported"]').val(0);
        return;
    }

    var data = {};
    data.source_id         = source_id;
    data.team_id           = team_id;
    data.marketer_id       = marketer_id;
    data.campaign_id       = campaign_id;
    data.clevel            = clevel;
    data.current_level     = current_level;
    data.subcampaign_id    = subcampaign_id;
    data.registered_date   = registered_date;
    data.landing_page      = landing_page;
    data.is_export         = is_export;
    data.search_text       = search;
    data.channel           = channel;

    $.get(url, data, function (data) {
        $('input[name="exported"]').val(data);

    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function enable_update() {
    // var is_checked = false;
    // $("input:checkbox[id=is_update]:checked").each(function () {
    //     is_checked = this.checked;
    // });
    //
    // var cnt =$('#table_contacts input[type=checkbox]:checked').length;
    //
    // if(is_checked){
    //     $('button#edit_contact').prop('disabled', false);
    //     $('button#edit_contact').removeClass('disabled');
    // }else{
    //     if(cnt == 0){
    //         $('button#update_contact').hide();
    //         $('button#edit_contact').show();
    //         $('button#edit_contact').prop('disabled', true);
    //         $('button#edit_contact').addClass('disabled');
    //     }
    // }
    var cnt = $('#table_contacts input[type=checkbox]:checked').length;
    var check_all = $('input[id=update_all]').is(':checked');

    if(cnt <= 0 && !check_all){
        $('button#update_contact').hide();
        $('button#edit_contact').show();
        setTimeout(function(){
            $('button#edit_contact').show().addClass('disabled');
            $('button#edit_contact').show().prop('disabled', true);
        }, 500);


    }

}

function edit(item, mode){
    var is_show = $('button#update_contact').is(':visible');
    $statusCell = $(item).parents().siblings('td.status');

    if(is_show || mode == 'all'){
        var is_checked = $(item).is(':checked');
        if(is_checked){
            addSelectStatus(item);
            addSelectStatusOLM(item);
            // addSelectChannel(item);

            var check_all = $('input[id=update_all]').is(':checked');
            if(check_all){
                $('select#status_update').first().focus(500);
            }else{
                $($statusCell).find('select#status_update').focus(500);
            }
        }else{
            $($statusCell).find('span#status').show();
            $($statusCell).find('select#status_update').remove();

            $($statusCell).find('span#olm_status').show();
            $($statusCell).find('select#olm_status_update').remove();
        }
    }
}

function addSelectStatus(item){
    $statusCell = $(item).parents().siblings('td.status');
    $status     = $($statusCell).find('input#old_status').val();
    $($statusCell).find('select#status_update').remove();

    $($statusCell).find('span#status').hide();
    if($status == 1){
        $($statusCell).append('' +
            '<select id="status_update" onchange="setAll(this);">' +
            '<option value="1" selected>Exported</option>' +
            '<option value="0">Not Export</option>' +
            '</select>' +
            '');
    }else{
        $($statusCell).append('' +
            '<select id="status_update" onchange="setAll(this);">' +
            '<option value="1">Exported</option>' +
            '<option value="0" selected>Not Export</option>' +
            '</select>' +
            '');
    }
}

function addSelectStatusOLM(item){
    $statusCell = $(item).parents().siblings('td.olm_status');
    $status     = $($statusCell).find('input#old_status_olm').val();
    $($statusCell).find('select#olm_status_update').remove();

    $($statusCell).find('span#olm_status').hide();
    if($status == 0){
        $($statusCell).append('' +
            '<select id="olm_status_update" onchange="setAll(this);">' +
            '<option value="0" selected>Success</option>' +
            '<option value="1">Duplicated</option>' +
            '<option value="2">Error</option>' +
            '<option value="3">Not Export</option>' +
            '</select>' +
            '');
    }else if ($status == 1){
        $($statusCell).append('' +
            '<select id="olm_status_update" onchange="setAll(this);">' +
            '<option value="0">Success</option>' +
            '<option value="1" selected>Duplicated</option>' +
            '<option value="2">Error</option>' +
            '<option value="3">Not Export</option>' +
            '</select>' +
            '');
    }else if ($status == 2){
        $($statusCell).append('' +
            '<select id="olm_status_update" onchange="setAll(this);">' +
            '<option value="0">Success</option>' +
            '<option value="1">Duplicated</option>' +
            '<option value="2" selected>Error</option>' +
            '<option value="3">Not Export</option>' +
            '</select>' +
            '');
    }else{
        $($statusCell).append('' +
            '<select id="olm_status_update" onchange="setAll(this);">' +
            '<option value="0">Success</option>' +
            '<option value="1">Duplicated</option>' +
            '<option value="2">Error</option>' +
            '<option value="3" selected>Not Export</option>' +
            '</select>' +
            '');
    }
}

function addSelectChannel(item){
    $statusCell = $(item).parents().siblings('td.channel');
    $status     = $($statusCell).find('input#channel').val();
    $($statusCell).find('select#channel_update').remove();

    $($statusCell).find('span#channel').hide();
    if($status == 1){
        $($statusCell).append('' +
            '<select id="channel_update" onchange="setAll(this);">' +
            '<option value="1" selected>Exported</option>' +
            '<option value="0">Not Export</option>' +
            '</select>' +
            '');
    }else{
        $($statusCell).append('' +
            '<select id="channel_update" onchange="setAll(this);">' +
            '<option value="1">Exported</option>' +
            '<option value="0" selected>Not Export</option>' +
            '</select>' +
            '');
    }
}

function setAll(item){
    $update_all = $('input[name="update_all"]').val();
    if($update_all == 1){
        $value = $(item).val();
        $("select#status_update").each(function () {
            $(this).val($value);
        });
        $('input[name="status_update_all"]').val($value);
    }
}

function updateStatusExport(id) {
    var status          = $('input[name=status_update_all]').val();
    var url             = $('input[name="update_status_export"]').val();
    var source_id       = $('input[name="source_id"]').val();
    var team_id         = $('input[name="team_id"]').val();
    var marketer_id     = $('input[name="marketer_id"]').val();
    var campaign_id     = $('input[name="campaign_id"]').val();
    var clevel          = $('input[name="clevel"]').val();
    var current_level   = $('input[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var subcampaign_id  = $('input[name="subcampaign_id"]').val();
    var old_status      = $('input[name="status"]').val();
    var landing_page    = $('select[name="landing_page"]').val();
    var channel         = $('select[name="channel_id"]').val();

    // if(id == '' && status == ''){
    //     countExported();
    //     setTimeout(function(){
    //         // if(old_status == '0'){
    //         //     $('input[name="exported"]').val(0);
    //         // }
    //         $('input#update_all').prop('checked', false); // Unchecks checkbox all
    //         initDataTable();
    //         $('.loading').hide();
    //         $('div#update_success').show();
    //     }, 1000);
    // }

    var data = {};
    data.id                = id;
    data.source_id         = source_id;
    data.team_id           = team_id;
    data.marketer_id       = marketer_id;
    data.campaign_id       = campaign_id;
    data.clevel            = clevel;
    data.current_level     = current_level;
    data.subcampaign_id    = subcampaign_id;
    data.registered_date   = registered_date;
    data.old_status        = old_status;
    data.new_status        = status;
    data.landing_page      = landing_page;
    data.channel           = channel;

    $.get(url, data, function (data) {
        countExported();
        setTimeout(function(){
            // if(old_status == '0'){
            //     $('input[name="exported"]').val(0);
            // }
            initDataTable();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all

            $('.loading').hide();
            $('div#update_success').show();
            $('div#export_success').hide();
        }, 1000);
    }).fail(
        function (err) {
            $('.loading').hide();
            alert('Cannot connect to server. Please try again later.');
        });

}

function exportToOLM(id) {
    var url             = $('input[name="export_to_olm_url"]').val();
    var source_id       = $('input[name="source_id"]').val();
    var team_id         = $('input[name="team_id"]').val();
    var marketer_id     = $('input[name="marketer_id"]').val();
    var campaign_id     = $('input[name="campaign_id"]').val();
    var clevel          = $('input[name="clevel"]').val();
    var current_level   = $('input[name="current_level"]').val();
    var registered_date = $('.registered_date').text();
    var subcampaign_id  = $('input[name="subcampaign_id"]').val();
    var old_status      = $('input[name="status"]').val();
    var landing_page    = $('select[name="landing_page"]').val();
    var channel         = $('select[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();

    var data = {};
    data.id                 = id;
    data.source_id          = source_id;
    data.team_id            = team_id;
    data.marketer_id        = marketer_id;
    data.campaign_id        = campaign_id;
    data.clevel             = clevel;
    data.current_level      = current_level;
    data.subcampaign_id     = subcampaign_id;
    data.registered_date    = registered_date;
    data.old_status         = old_status;
    data.new_status         = status;
    data.landing_page       = landing_page;
    data.channel            = channel;
    data.olm_status         = olm_status;

    $.get(url, data, function (data) {
        countExported();
        setTimeout(function(){
            initDataTable();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all

            $('div#update_success').hide();
            $('div#export_success').show();
            $('.loading').hide();
        }, 1000);
    }).fail(
        function (err) {
            $('.loading').hide();
            alert('Cannot connect to server. Please try again later.');
        });

}
