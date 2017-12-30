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

        $('#createTeamModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
            var modal = $(this);
            if (itemId) {
                $.get('{{ route("team-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var team = data.team;

                        modal.find('.modal-title').text('Edit Team');
                        modal.find('input[name=team_id]').val(itemId);
                        modal.find('input[name=name]').val(team.name);
                        modal.find('select[name=source]').val(team.source_id);
                        modal.find('textarea[name=description]').html(team.description);
                        var members = modal.find('input[name=members]').selectize();
                        members[0].selectize.setValue(team.member_ids_array);

                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Team');
                modal.find('[type=submit]').html('Create');
                $('#form-team')[0].reset();
                modal.find('input[name=team_id]').val('');
                modal.find('textarea[name=description]').val('');
                var members = modal.find('input[name=members]').selectize();
                members[0].selectize.setValue([]);
            }
        })

        $('#form-team').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.team_id = $(this).find('[name=team_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.source = $(this).find('[name=source]').val();
            data.description = $(this).find('[name=description]').val();
            data.members = $(this).find('[name=members]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!data.name || !data.description){
                $('#form-team-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
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
                    $('#form-team-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-team-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
            });
        })

    })
</script>