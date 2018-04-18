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
            var itemId = button.data('item-id');
            var modal = $(this);
            if (itemId) {
                $.get('{{ route("config-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var config = data.config;

                        modal.find('.modal-title').text('Edit Config');
                        modal.find('input[name=config_id]').val(itemId);
                        modal.find('input[name=name]').val(config.name);
                        modal.find('input[name=key]').val(config.key);
                        modal.find('input[name=value]').val(config.value);
                        modal.find('textarea[name=description]').html(config.description);
                        modal.find('select[name=active]').val(config.active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Config');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-config')[0].reset();
                modal.find('input[name=config_id]').val('');
            }
        })

        $('#form-config').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.config_id = $(this).find('[name=config_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.key = $(this).find('[name=key]').val();
            data.value = $(this).find('[name=value]').val();
            data.description = $(this).find('[name=description]').val();
            data.active = $(this).find('[name=active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!data.key || !data.value){
                $('#form-config-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
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
                    $('#form-config-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-config-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
            });
        })
    })

</script>