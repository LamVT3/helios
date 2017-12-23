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

        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
            var modal = $(this);
            if (itemId) {
                $.get('{{ route("subcampaign-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var subcampaign = data.subcampaign;

                        modal.find('.modal-title').text('Edit Subcampaign');
                        modal.find('input[name=subcampaign_id]').val(itemId);
                        modal.find('input[name=name]').val(subcampaign.name);
                        modal.find('input[name=code]').val(subcampaign.code);
                        modal.find('textarea[name=description]').html(subcampaign.description);
                        modal.find('select[name=is_active]').val(subcampaign.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Subcampaign');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-subcampaign')[0].reset();
                modal.find('input[name=subcampaign_id]').val('');
            }
        })

        $('#form-subcampaign').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.subcampaign_id = $(this).find('[name=subcampaign_id]').val();
            data.campaign_id = $(this).find('[name=campaign_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.code = $(this).find('[name=code]').val();
            data.description = $(this).find('[name=description]').val();
            data.is_active = $(this).find('[name=is_active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!data.name || !data.code || !data.description){
                $('#form-subcampaign-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                return false;
            }
            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    location.href = data.url;
                }else{
                    $('#form-subcampaign-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-subcampaign-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
            });
        })

    })
</script>