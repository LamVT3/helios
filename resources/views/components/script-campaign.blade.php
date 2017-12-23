<script>
    $(function(){

        /*$('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemName = button.data('item-name') // Extract info from data-* attributes
            var itemId = button.data('item-id')
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('Bạn có chắc chắn xóa review của "' + itemName + '"?')
            modal.find('input[name=id]').val(itemId)
        })*/

        $('#select-campaign').change(function () {
            if($(this).val() == "old"){
                $("#new-campaign").hide();
                $("#old-campaign").show();
                $('#select-subcampaign [value=old]').removeAttr('disabled');
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
                $('#select-subcampaign').val("new");
                $('#select-subcampaign').change();
            }else{
                $("#new-campaign").show();
                $("#old-campaign").hide();
                $('#select-subcampaign').val("new");
                $('#select-subcampaign').change();
                $('#select-subcampaign [value=old]').attr('disabled', 'disabled');
                $('#select-ad [value=skip]').removeAttr('disabled');
            }
        })

        $('#campaign').change(function () {
            $.get('{{ route("campaign-get", "") }}/' + $(this).val(), {}, function (data) {
                if (data.type && data.type == 'success') {
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
        })

        $('#select-subcampaign').change(function () {
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
            }else{
                $("#new-ad").hide();
            }
        })

        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
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
                modal.find('.modal-title').text('Create Campaign');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-campaign')[0].reset();
                modal.find('input[name=campaign_id]').val('');
            }
        })

        $('#form-campaign').submit(function (e) {

            e.preventDefault();
            var data = {};

            data.select_campaign = $(this).find('[name=select_campaign]').val();
            data.select_subcampaign = $(this).find('[name=select_subcampaign]').val();
            data.select_ad = $(this).find('[name=select_ad]').val();

            data.campaign_name = $(this).find('[name=campaign_name]').val();
            data.medium = $(this).find('[name=medium]').val();
            data.campaign = $(this).find('[name=campaign]').val();

            data.subcampaign_name = $(this).find('[name=subcampaign_name]').val();
            data.subcampaign = $(this).find('[name=subcampaign]').val();

            data.ad_name = $(this).find('[name=ad_name]').val();
            data.landing_page = $(this).find('[name=landing_page]').val();

            data._token = $(this).find('[name=_token]').val();

            var message = "", error = false;
            //console.log(data);
            if(data.select_campaign == "new" && !$.trim(data.campaign_name)){
                 message = 'Please enter a campaign name';
                 error = true;
            }else if(data.select_subcampaign == "new" && !$.trim(data.subcampaign_name)){
                message = 'Please enter a subcampaign name';
                error = true;
            }else if(data.select_ad == "new" && !$.trim(data.ad_name)){
                message = 'Please enter an ad name';
                error = true;
            }else if(data.select_campaign == "old" && !$.trim(data.campaign)){
                message = 'Please choose a campaign';
                error = true;
            }else if(data.select_subcampaign == "old" && !$.trim(data.subcampaign)){
                message = 'Please choose a subcampaign';
                error = true;
            }

            if(error){
                $('#form-campaign-alert').html('<div class="alert alert-danger">' + message + '</div>');
                $('.modal').scrollTop(0);
                return false;
            }

            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    /*$('#form-review').find("input, textarea").val("");
                    $('#form-review-alert').html('<div class="alert alert-success">' + data.message + '</div>');
                    $('.starrr').find('.glyphicon-star').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    $('#meaning').html('');*/
                    location.href = data.url;
                }else{
                    $('#form-campaign-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-campaign-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
            });
        })

    })
</script>