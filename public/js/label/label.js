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

    function tranfer_date_span(start, end) {
        if(start == null && end == null){
            $('#tranfer_date span').html('');
        }else
            $('#tranfer_date span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
    }

    tranfer_date_span(null, null);

    $('#tranfer_date').daterangepicker({
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
    }, tranfer_date_span);

    $('#tranfer_date').on('apply.daterangepicker', function(ev, picker) {
        $('#tranfer_date span').html(picker.startDate.format('D/M/Y') + '-' + picker.endDate.format('D/M/Y'));
        $('input[name=tranfer_date]').val(picker.startDate.format('D/M/Y') + '-' + picker.endDate.format('D/M/Y'));
    });

    $('#tranfer_date').on('cancel.daterangepicker', function(ev, picker) {
        $('#tranfer_date span').html('');
        $('input[name=tranfer_date]').val('');
    });

});

$(document).ready(function () {
    $('#source_id').change(function (e) {
        resetChannel();
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

            // $('#channel_id').html(response.content_channel);
            // $("#channel_id").select2();
        });
    })

    $('#team_id').change(function (e) {
        resetChannel();
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
            // $('#channel_id').html(response.content_channel);
            // $("#channel_id").select2();
        });
    })

    $('#marketer_id').change(function (e) {
        resetChannel();
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
            // $('#channel_id').html(response.content_channel);
            // $("#channel_id").select2();
        });
    })

    $('#campaign_id').change(function (e) {
        resetChannel();
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
            // $('#channel_id').html(response.content_channel);
            // $("#channel_id").select2();
        });
    })

    $('#subcampaign_id').change(function (e) {
        resetChannel();
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
            // $('#channel_id').html(response.content_channel);
            // $("#channel_id").select2();
        });
    })

    // HoaTV: reset channel when change another dropdown
    function resetChannel(){
        var $select = $('#channel_id').selectize();
        var control = $select[0].selectize;
        control.clear();
    }

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
        countExported();

        setTimeout(function(){
            // initDataTable();
            // countExported();
            initDataTable();
        },1000);
    });

    $('input#export_sale_limit').change(function (e) {
        if($(this).val() > 0){
            $('input#export_sale_limit').removeClass('input_error');
            $('em#export_sale_limit-error').hide();
        }else{
            $('input#export_sale_limit').addClass('input_error');
            $('em#export_sale_limit-error').show();
        }
    });

    $('input#send_sms_limit').change(function (e) {
        if($(this).val() > 0){
            $('input#send_sms_limit').removeClass('input_error');
            $('em#send_sms_limit').hide();
        }else{
            $('input#send_sms_limit').addClass('input_error');
            $('em#send_sms_limit').show();
        }
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
        countExported();

        setTimeout(function(){
            // initDataTable();
            // countExported();
            initDataTable();
        },1000);
    });

    $('button#confirm_export').click(function (e) {
        e.preventDefault();
        var id = '';
        $("input:checkbox[id=is_update]:checked").each(function () {
            id += $(this).val() + ',';
        });

        $('input[name=contact_id]').val(id);

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
        var limit = $('input#export_sale_limit').val();
        if(limit > 0){
            $('.loading').show();
            var is_update_all   = $('input[id=update_all]').is(':checked');
            var id      = {};

            if(is_update_all){
                id = 'All';
            }
            else{
                $("input:checkbox[id=is_update]:checked").each(function () {
                    id[$(this).val()] = $(this).val();
                });
            }

            $('.loading_modal').show();
            $('#confirm_export_to_olm').prop('disabled', true);
            $('#close_modal_export_to_olm').prop('disabled', true);

            exportToOLM(id);
            // updateStatusExport(id);
        }else{
            $('input#export_sale_limit').addClass('input_error');
            $('em#export_sale_limit-error').show();
        }
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
                document.getElementById("import_text").innerHTML = result;
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
                document.getElementById("import_text").innerHTML = result;
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
        var id      = {};
        $("input:checkbox[id=is_update]:checked").each(function () {

            let label1_origin   = $(this).parent().siblings().find('input#label1_origin').val();
            let label2_origin   = $(this).parent().siblings().find('input#label2_origin').val();
            let label3_origin   = $(this).parent().siblings().find('input#label3_origin').val();
            let label4_origin   = $(this).parent().siblings().find('input#label4_origin').val();
            let label5_origin   = $(this).parent().siblings().find('input#label5_origin').val();
            let label6_origin   = $(this).parent().siblings().find('input#label6_origin').val();
            let label7_origin   = $(this).parent().siblings().find('input#label7_origin').val();
            let label8_origin   = $(this).parent().siblings().find('input#label8_origin').val();
            let label9_origin   = $(this).parent().siblings().find('input#label9_origin').val();
            let label10_origin  = $(this).parent().siblings().find('input#label10_origin').val();

            id[$(this).val()] = {
                'c3_label1_origin':     label1_origin,
                'c3_label2_origin':     label2_origin,
                'c3_label3_origin':     label3_origin,
                'c3_label4_origin':     label4_origin,
                'c3_label5_origin':     label5_origin,
                'c3_label6_origin':     label6_origin,
                'c3_label7_origin':     label7_origin,
                'c3_label8_origin':     label8_origin,
                'c3_label9_origin':     label9_origin,
                'c3_label10_origin':    label10_origin,
            };
        });

        updateContacts(id);
        // }
    });

    $('input[id=update_all]').click(function () {
        var is_checked = this.checked;
        $('input:checkbox[id=is_update]').prop('checked', this.checked);
        if(is_checked){
            $('input[name="update_all"]').val(1);
            $('button#edit_contact').show().removeClass('disabled');
            $('button#edit_contact').show().prop('disabled', false);
            if( $('button#update_contact').is(':visible')){
                $('button#edit_contact').addClass('disabled');
                $('button#edit_contact').prop('disabled', true);
                $('button#edit_contact').hide();
                $("input:checkbox[id=is_update]").each(function () {
                    edit(this, 'all');
                });
            }
        }else{
            $("input:checkbox[id=is_update]").each(function () {
                edit(this, 'all');
            });
            $('input[name="update_all"]').val(0);
            $('button#edit_contact').show();
            $('button#update_contact').hide();
        }

        enable_update();
    });

    $('button#edit_contact').click(function () {
        $(this).hide();
        $('button#update_contact').show();
        $("input:checkbox[id=is_update]").each(function () {
            edit(this);
        });
    });

    $('button#confirm_send_sms').click(function (e) {
        e.preventDefault();
        var limit = $('input#send_sms_limit').val();
        if(limit > 0){
            if (confirm('Are you sure you want to send SMS for these contacts ?')) {
                $('.loading').show();
                var is_update_all   = $('input[id=update_all]').is(':checked');
                var id  = {};

                if(is_update_all){
                    id = 'All';
                }
                else{
                    $("input:checkbox[id=is_update]:checked").each(function () {
                        id[$(this).val()] = $(this).val();
                    });
                }

                $('.loading_modal').show();
                $('#confirm_send_sms').prop('disabled', true);
                $('#close_modal_send_sms').prop('disabled', true);

                sendSMS(id);
            }
        }else{
            $('input#send_sms_limit').addClass('input_error');
            $('em#send_sms_limit-error').show();
        }
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
    // HoaTV fix change to multi select dropdown
    // var channel         = $('select[name="channel_id"]').val();
    var channel         = $('input[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();
    var tranfer_date    = $('.tranfer_date_span').text();
    var mailchimp_expired = $('select[name="mailchimp_expired"]').val();

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
    $('input[name="mailchimp_expired"]').val(mailchimp_expired);

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
            $('.loading').show();
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
            var cnt_exported_to_olm     = '<strong>'+ exported_olm + ' contact(s) export to Sales'+'</strong>';
            $('p#cnt_export_to_olm').html(cnt_exported_to_olm);

            responsiveHelper_table_campaign.respond();
            setTimeout("$('.loading').hide();", 500);
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
                    d.olm_status        = olm_status ? parseInt(olm_status) : '',
                    d.tranfer_date      = tranfer_date,
                    d.mailchimp_expired = mailchimp_expired
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

            { "data" : 'c3_label1_origin',  'className' : "label1_origin",  "defaultContent": "1"},
            { "data" : 'c3_label2_origin',  'className' : "label2_origin",  "defaultContent": "2" },
            { "data" : 'c3_label3_origin',  'className' : "label3_origin",  "defaultContent": "3" },
            { "data" : 'c3_label4_origin',  'className' : "label4_origin",  "defaultContent": "4" },
            { "data" : 'c3_label5_origin',  'className' : "label5_origin",  "defaultContent": "5" },
            { "data" : 'c3_label6_origin',  'className' : "label6_origin",  "defaultContent": "6" },
            { "data" : 'c3_label7_origin',  'className' : "label7_origin",  "defaultContent": "7" },
            { "data" : 'c3_label8_origin',  'className' : "label8_origin",  "defaultContent": "8" },
            { "data" : 'c3_label9_origin',  'className' : "label9_origin",  "defaultContent": "9" },
            { "data" : 'c3_label10_origin', 'className' : "label10_origin", "defaultContent": "0" },

            { "data" : 'age'},
            { "data" : 'submit_time'},
            { "data" : 'clevel'},
            { "data" : 'current_level',     "defaultContent": "-"},
            { "data" : 'ad_name', 'className' : "ads_long" },
            {
                "data"      : 'channel_name',
                'className' : "channel",
                "render"    : function ( data, type, row, meta ) {
                    return '<span id="channel" >' + data + '</span><input type="hidden" id="old_channel" value="' + data + '">';

                }
            },
            { "data" : 'source_name',       "defaultContent": "-"},
            { "data" : 'team_name',         "defaultContent": "-"},
            { "data" : 'marketer_name',     "defaultContent": "-"},
            { "data" : 'campaign_name',     "className" : "ads_long" },
            { "data" : 'subcampaign_name',  "className" : "ads_long" },
            { "data" : 'landing_page',      "defaultContent": "-"},
            {
                "data" : 'invalid_reason',
                'className' : "invalid_reason",
                "render"    : function ( data, type, row, meta ) {
                    if(data){
                        return '<span id="invalid_reason" >' + data + '</span>';
                    }else{
                        return '<span id="invalid_reason" >-</span>';
                    }
                }
            },
            {
                "data" : 'name',
                "render": function ( data, type, row, meta ) {
                    return '<a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="' + data[0] + '">' +
                        '<i class="fa fa-eye"></i><b style="margin-left: 5px;">' + data[2] + '</b></a>';
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
                    else if(data == 2){
                        status = '<span id="olm_status">Error</span><input type="hidden" id="old_status_olm" value="2">';
                    }
                    else if(data == 3){
                        status = '<span id="olm_status">Error</span><input type="hidden" id="old_status_olm" value="3">';
                    }
                    else{
                        status = '<span id="olm_status">Not Exported</span><input type="hidden" id="old_status_olm" value="-1">';
                    }
                    return status;
                }
            },
            { "data" : 'export_sale_date',  "defaultContent": "-"},
            { "data" : 'send_sms',          "defaultContent": "-"},
            { "data" : 'mailchimp_expired', "defaultContent": "-"},
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
                else if(data['olm_status'] == 2){
                    $(row).addClass('olm_status_error');
                }
            }
        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            var exported        = $('input[name=export_to_olm]').val();
            var total_contacts  = iTotal - exported;

            $('input[name=total_contacts]').val(total_contacts);
            return sPre;
        },
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
    // HoaTV fix change to multiple select channel
    // var channel         = $('select[name="channel_id"]').val();
    var channel         = $('input[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();
    var tranfer_date    = $('.tranfer_date_span').text();
    var mailchimp_expired = $('select[name="mailchimp_expired"]').val();

    if(is_export === '0'){
        $('input[name="exported"]').val(0);
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
    data.olm_status         = olm_status ? parseInt(olm_status) : '';
    data.tranfer_date       = tranfer_date;
    data.mailchimp_expired  = mailchimp_expired;

    $.get(url, data, function (data) {
        $('input[name="exported"]').val(data.to_excel);
        $('input[name="export_to_olm"]').val(data.to_olm);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}

function enable_update() {
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
    var is_show         = $('button#update_contact').is(':visible');
    // var statusCell      = $(item).parents().siblings('td.status');
    // var statusOLMCell   = $(item).parents().siblings('td.olm_status');
    // var channelCell     = $(item).parents().siblings('td.channel');
    // HoaTV change c3bg
    // var invalidReasonCell     = $(item).parents().siblings('td.invalid_reason');

    if(is_show || mode == 'all'){
        var is_checked = $(item).is(':checked');
        if(is_checked){
            addLabelOrigin(item);
        }else{
            removeLabelOrigin(item)
        }
    }

    var cnt = $('input[id=is_update]:checked').length;
    if(cnt <= 0){
        $('input[name="update_all"]').val(0);
        $('input#update_all').prop('checked', false);
        $('button#edit_contact').show();
        $('button#update_contact').hide();
    }
}

function addLabelOrigin(item){
    for (var i = 1; i <= 10; i++) {
        let id = 'label' + i + '_origin';
        let td  = $(item).parents().siblings('td.' + id);
        let td_value =  $(td).text();
        $(td).find('input#' + id).remove();
        $(td).find('input#old_' + id).remove();
        $(td).text('');
        $(td).append('<input id="' + id + '" class="form-control" style="max-width: 100px" type="text" value="' + td_value + '" onchange="setAllLabel(this);">');
        $(td).append('<input id="old_' + id + '" class="form-control" type="hidden" value="' + td_value + '">');
    }
}

function removeLabelOrigin(item){
    for (var i = 1; i <= 10; i++) {
        let id = 'label' + i + '_origin';
        let td  = $(item).parents().siblings('td.' + id);
        let td_value =  $(td).find('input#old_' + id).val();
        $(td).find('input#' + id).remove();
        $(td).find('input#old' + id).remove();
        $(td).text(td_value);
    }
}

function setAll(item){
    var update_all = $('input[name="update_all"]').val();
    if(update_all == 1){
        var value = $(item).val();
        $("select#status_update").each(function () {
            $(this).val(value);
        });
        // $('input[name="status_update_all"]').val(value);
    }
}

function setAllStatusOLM(item){
    var update_all = $('input[name="update_all"]').val();
    if(update_all == 1){
        var value = $(item).val();
        $("select#olm_status_update").each(function () {
            $(this).val(value);
        });
        // $('input[name="status_update_all"]').val(value);
    }
}

function setAllChannel(item){
    var update_all = $('input[name="update_all"]').val();
    if(update_all == 1){
        var value = $(item).val();
        $("select#channel_update").each(function () {
            $(this).val(value);
        });
        // $('input[name="status_update_all"]').val(value);
    }
}

function setAllLabel(item){
    let update_all = $('input[name="update_all"]').val();
    if(update_all == 1){
        let value   = $(item).val();
        let id      = $(item).attr('id');
        $("input#" + id).each(function () {
            $(this).val(value);
        });
        // $("input#old_" + id).val(value);
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function updateContacts(id) {
    let url = $('input[name="update_label_origin"]').val();
    console.log(url);
    let data = {};
    data.id = id;

    $.post(url, data, function (data) {
        console.log(data);

        setTimeout(function(){
            // if(old_status == '0'){
            //     $('input[name="exported"]').val(0);
            // }
            initDataTable();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all

            $('div#update_success').show();
        }, 1000);
    }).fail(
        function (err) {
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
    // HoaTV fix change to multiple select channel
    // var channel         = $('select[name="channel_id"]').val();
    var channel         = $('input[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();
    var limit           = $('input#export_sale_limit').val();
    var export_sale_date = $('input#export_sale_date').val();
    var export_sale_sort = $("input[name='export_sale_sort']:checked"). val();
    var tranfer_date    = $('.tranfer_date_span').text();
    var mailchimp_expired = $('select[name="mailchimp_expired"]').val();

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
    data.olm_status         = olm_status ? parseInt(olm_status) : '';
    data.limit              = limit;
    data.export_sale_date   = export_sale_date;
    data.export_sale_sort   = export_sale_sort;
    data.mailchimp_expired  = mailchimp_expired;

    $.get(url, data, function (data) {
        countExported();
        showModalExportToOLM(data);
        setTimeout(function(){
            initDataTable();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all

            $('div#update_success').hide();
            $('div#export_success').hide();
            $('.loading_modal').hide();
            $('#confirm_export_to_olm').prop('disabled', false);
            $('#close_modal_export_to_olm').prop('disabled', false);

        }, 1000);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });

}

function showSendSMSResultModal(data){
    $('#total_send').html('<h2 style="font-weight:800">Total: ' + data.total +'</h2' );
    $('#send_pass').html('- Contacts success: ' + data.send_pass);
    $('#send_fail').html('- Contacts fail:' + data.send_fail);
    $('#used_credit').html('- Used credit: ' + data.used_credit);
    $('#mySendSMSModal').modal('hide');
    $('#mySendSMSResultModal').modal('show');
}

function showModalExportToOLM(data){
    $('#total_contact').html('<h2 style="font-weight:800">Total: ' + data.cnt_total +'</h2' );
    $('#contact_success').html('- ' + data.cnt_success + ' contacts success');
    $('#contact_duplicate').html('- ' + data.cnt_duplicate + ' contacts duplicated');
    $('#contact_error').html('- ' + data.cnt_error + ' contacts error');
    $('#myExportToOLMModal').modal('hide');
    $('#myCountExportToOLMModal').modal('show');
}

$(function() {
    $('#myExportToOLMModal').on('shown.bs.modal', function () {
        $('#export_sale_date').daterangepicker({
            "singleDatePicker": true,
            "timePicker24Hour": true,
            timePicker: true,
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('second'),
            "endDate": moment().startOf('second'),
            locale: {
                format: 'DD/MM/YYYY HH:mm:ss'
            }
        }, function(start, end, label) {

        });

        var checked = $("input:checkbox[id=is_update]:checked").length;
        if(checked > 0){
            $('input#export_sale_limit').val(checked);
            $('input#send_sms_limit').val(checked);
        }else{
            var total =  $('input[name=total_contacts]').val();
            $('input#export_sale_limit').val(total);
            $('input#send_sms_limit').val(total);
        }
    });

    $('#mySendSMSModal').on('shown.bs.modal', function () {
        var checked = $("input:checkbox[id=is_update]:checked").length;
        if(checked > 0){
            $('input#send_sms_limit').val(checked);
        }else{
            var total =  $('input[name=total_contacts]').val();
            $('input#send_sms_limit').val(total);
        }

        var url = $('input[name="get_balance_url"]').val();

        $.get(url, function (data) {
            $('span#standard_balance').text(data.standard_balance);
            $('span#premium_balance').text(data.premium_balance);
        }).fail(
            function (err) {
                alert('Cannot connect to server. Please try again later.');
            });
    });

});

function sendSMS(id) {
    var url             = $('input[name="send_sms_url"]').val();
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
    var channel         = $('input[name="channel_id"]').val();
    var olm_status      = $('select[name="olm_status"]').val();
    var limit           = $('input#send_sms_limit').val();
    var export_sale_sort = $("input[name='send_sms_sort']:checked"). val();
    var tranfer_date    = $('.tranfer_date_span').text();
    var mailchimp_expired = $('select[name="mailchimp_expired"]').val();

    var data = {};
    data._token             = $('#form-send-sms').find('[name=_token]').val();
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
    data.olm_status         = olm_status ? parseInt(olm_status) : '';
    data.limit              = limit;
    data.export_sale_sort   = export_sale_sort;
    data.tranfer_date       = tranfer_date;
    data.mailchimp_expired  = mailchimp_expired;

    $.post(url, data, function (data) {
        setTimeout(function(){
            showSendSMSResultModal(data);
            initDataTable();
            $('input#update_all').prop('checked', false); // Unchecks checkbox all

            $('div#update_success').hide();
            $('div#export_success').hide();
            $('.loading_modal').hide();
            $('#confirm_send_sms').prop('disabled', false);
            $('#close_modal_send_sms').prop('disabled', false);

            $('#mySendSMSModal').modal('hide');
        }, 1000);
    }).fail(
        function (err) {
            alert('Cannot connect to server. Please try again later.');
        });
}
