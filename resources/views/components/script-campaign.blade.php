<script>
    $(function(){

        var errorClass = 'invalid';
        var errorElement = 'em';

        $.validator.addMethod( "alphanumeric", function( value, element ) {
            return this.optional( element ) || /^[\w.]+$/i.test( value );
        }, "Letters, numbers, dots and underscores only please" );

        $('#form-campaign').validate({
            errorClass: errorClass,
            errorElement: errorElement,
            highlight: function (element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
                rm_success_style();
            },
            unhighlight: function (element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
                rm_success_style();
            },

            // Rules for form validation
            rules: {
                campaign_name: {
                    required: true,
                    alphanumeric: true
                },
                medium: {
                    alphanumeric: true
                },
                subcampaign_name: {
                    required: true,
                    alphanumeric: true
                },
                ad_name: {
                    required: true,
                    alphanumeric: true
                },
                mol_link_tracking: {
                    alphanumeric: true
                },
                source: {
                    alphanumeric: true
                },
                landing_page: {
                    alphanumeric: true
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });

        $('#source').change(function () {
            $.get('{{ route("ajax-getCampaigns", "") }}/' + $(this).val(), {}, function (data) {
                if (data.type && data.type == 'success') {
                    //var teams = data.teams;
                    var campaigns = data.campaigns;

                    var selectize = $('input[name=campaign]').selectize()[0].selectize;
                    selectize.clearCache('option');
                    selectize.clearOptions();
                    selectize.addOption(campaigns);
                    selectize.refreshOptions(true);

                    /*var options = '';
                    for(var item in teams){
                        options += '<option value="' + teams[item].team_id + '">' + teams[item].team_name + '</option>';
                    }

                    $('#team').html(options);*/
                } else {
                    alert("Could not get subcampaigns. Please try again.")
                }
            })
        })

        // Choose create a new campaign or choose from existing ones
        $('#campaign-type').change(function () {
            if($(this).val() == "old"){
                $("#new-campaign").hide();
                $("#old-campaign").show();
                $('#subcampaign-type [value=old]').removeAttr('disabled');
                $.get('{{ route("subcampaign-get", "all") }}', {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var subcampaigns = data.subcampaign;

                        $('input[name=subcampaign]').selectize({
                            valueField: '_id',
                            labelField: 'name',
                            searchField: ['name'],
                            options: subcampaigns,
                            maxItems: 1
                        });
                    } else {
                        alert("Could not get subcampaigns. Please try again.")
                    }
                })
                $('#subcampaign-type').val("new");
                $('#subcampaign-type').change();
            }else{
                $("#new-campaign").show();
                $("#old-campaign").hide();
                $('#subcampaign-type').val("new");
                $('#subcampaign-type').change();
                $('#subcampaign-type [value=old]').attr('disabled', 'disabled');
                $('#select-ad [value=skip]').removeAttr('disabled');
                $('#subcampaign-type [value=skip]').removeAttr('disabled');
            }
        })

        // Select a campaign
        $('#campaign').change(function () {
            if($(this).val()) {
                $.get('{{ route("ajax-getSubcampaigns", "") }}/' + $(this).val(), {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var campaign = data.campaign;
                        var subcampaigns = data.subcampaigns;

                        var selectize = $('input[name=subcampaign]').selectize()[0].selectize;
                        selectize.clearCache('option');
                        selectize.clearOptions();
                        selectize.addOption(subcampaigns);
                        selectize.refreshOptions(true);

                    } else {
                        alert("Could not get subcampaigns. Please try again.")
                    }
                })
            } else {
                var selectize = $('input[name=subcampaign]').selectize()[0].selectize;
                selectize.clearCache('option');
                selectize.clearOptions();
            }
        })

        // Choose create a new subcampaign or choose from existing ones
        $('#subcampaign-type').change(function () {
            if($(this).val() == "old"){
                $("#new-subcampaign").hide();
                $("#old-subcampaign").show();
                $('#select-ad').val("new");
                $('#select-ad').change();
                $('#select-ad [value=skip]').attr('disabled', 'disabled');
            }else if($(this).val() == "new"){
                $("#new-subcampaign").show();
                $("#old-subcampaign").hide();
                $('#select-ad [value=skip]').removeAttr('disabled');
            }else{
                $("#new-subcampaign").hide();
                $("#old-subcampaign").hide();
                if($('#campaign-type').val() === "old"){
                    $('#select-ad [value=skip]').attr('disabled', 'disabled');
                    $('#select-ad').val("new");
                    $('#select-ad').change();
                }
            }
        })

        /*$('#subcampaign').change(function () {
            var selectize = $('input[name=subcampaign]').selectize()[0].selectize;
            var item = selectize.options[$(this).val()];

            console.log(item);
            var campaign = $('#campaign').selectize();
            campaign[0].selectize.setValue([item.campaign_id]);
        })*/

        $('#select-ad').change(function () {
            if($(this).val() == "new"){
                $("#new-ad").show();
                $('#subcampaign-type [value=skip]').removeAttr('disabled');
            }else{
                $("#new-ad").hide();
                if($('#campaign-type').val() === "old"){
                    $('#subcampaign-type [value=skip]').attr('disabled', 'disabled');
                    $('#subcampaign-type').val('new');
                    $('#subcampaign-type').change();
                }
            }
        })

        /*$('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
            var itemType = button.data('item-type')
            var modal = $(this);
            if (itemId) {
                $.get('{{ route("campaign-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var campaign = data.campaign;

                        modal.find('.modal-title').text('Edit Campaign');
                        modal.find('input[name=campaign_id]').val(itemId);
                        modal.find('input[name=name]').val(campaign.name);
                        modal.find('input[name=code]').val(campaign.code);
                        modal.find('textarea[name=description]').html(campaign.description);
                        modal.find('select[name=is_active]').val(campaign.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create ' + itemType);
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-campaign')[0].reset();
                modal.find('input[name=campaign_id]').val('');
            }
        })*/

        $('#form-campaign').submit(function (e) {

            e.preventDefault();

            var data = {};

            data.source = $(this).find('[name=source]').val();
            data.team = $(this).find('[name=team]').val();
            data.campaign_type = $(this).find('[name=campaign_type]').val();
            data.subcampaign_type = $(this).find('[name=subcampaign_type]').val();
            data.select_ad = $(this).find('[name=select_ad]').val();

            data.campaign_name = $(this).find('[name=campaign_name]').val();
            data.medium = $(this).find('[name=medium]').val();
            data.campaign = $(this).find('[name=campaign]').val();

            data.subcampaign_name = $(this).find('[name=subcampaign_name]').val();
            data.subcampaign = $(this).find('[name=subcampaign]').val();

            data.ad_name = $(this).find('[name=ad_name]').val();
            data.mol_link_tracking = $(this).find('[name=mol_link_tracking]').val();
            data.landing_page = $(this).find('[name=landing_page]').val();

            data.current_url = "{{ url()->current() }}";

            data._token = $(this).find('[name=_token]').val();

            var message = "", error = false;

            if(!$(this).valid()) return false;

            if(data.campaign_type == "new" && !$.trim(data.campaign_name)){
                 message = 'Please enter a campaign name';
                 error = true;
            }else if(data.subcampaign_type == "new" && !$.trim(data.subcampaign_name)){
                message = 'Please enter a subcampaign name';
                error = true;
            }else if(data.select_ad == "new" && !$.trim(data.ad_name)){
                message = 'Please enter an ad name';
                error = true;
            }else if(data.campaign_type == "old" && !$.trim(data.campaign)){
                message = 'Please choose a campaign';
                error = true;
            }else if(data.subcampaign_type == "old" && !$.trim(data.subcampaign)){
                message = 'Please choose a subcampaign';
                error = true;
            }

            if(error){
                $('#form-campaign-alert').html('<div class="alert alert-danger">' + message + '</div>');
                $('.modal').scrollTop(0);
                return false;
            }

            var btn = $(this).find('[type=submit]');
            btn.attr('disabled', 'disabled');

            $('.loading').show();

            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    /*$('#form-review').find("input, textarea").val("");
                    $('#form-review-alert').html('<div class="alert alert-success">' + data.message + '</div>');
                    $('.starrr').find('.glyphicon-star').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    $('#meaning').html('');*/
                    location.href = data.url;
                }else{
                    btn.removeAttr('disabled');
                    $('#form-campaign-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
                    $('.loading').hide();
                }
            }).fail(
                function (err) {
                    console.log(err);
                    btn.removeAttr('disabled');
                    $('#form-campaign-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
                    $('.loading').hide();
            });
        })

        function rm_success_style(){
            $('#campaign-type').parent('label.state-success').removeClass('state-success');
            $('#subcampaign-type').parent('label.state-success').removeClass('state-success');
            $('#select-ad').parent('label.state-success').removeClass('state-success');
        }

    })
</script>