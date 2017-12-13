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
                $.get('{{ route("ads-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var ads = data.ads;

                        modal.find('.modal-title').text('Edit Ads');
                        modal.find('input[name=ads_id]').val(itemId);
                        modal.find('input[name=name]').val(ads.name);
                        modal.find('input[name=landing_page]').val(ads.landing_page);
                        modal.find('input[name=keyword]').val(ads.keyword);
                        modal.find('textarea[name=description]').html(ads.description);
                        modal.find('select[name=is_active]').val(ads.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Ads');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-ads')[0].reset();
                modal.find('input[name=ads_id]').val('');
            }
        })

        $('#form-ads').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.ads_id = $(this).find('[name=ads_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.landing_page = $(this).find('[name=landing_page]').val();
            data.keyword = $(this).find('[name=keyword]').val();
            data.description = $(this).find('[name=description]').val();
            data.is_active = $(this).find('[name=is_active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!data.name || !data.url){
                $('#form-ads-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
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
                    $('#form-ads-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-ads-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
            });
        })

    })
</script>