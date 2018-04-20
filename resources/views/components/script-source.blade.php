<script>
    $(function(){
        var errorClass = 'invalid';
        var errorElement = 'em';

        $.validator.addMethod( "alphanumeric", function( value, element ) {
            return this.optional( element ) || /^\w+$/i.test( value );
        }, "Letters, numbers, and underscores only please" );

        $('#form-source').validate({
            errorClass: errorClass,
            errorElement: errorElement,
            highlight: function (element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function (element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },

            // Rules for form validation
            rules: {
                name: {
                    required: true,
                    alphanumeric: true
                },
                description: {
                    required: true,
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });

        $('#form-team').validate({
            errorClass: errorClass,
            errorElement: errorElement,
            highlight: function (element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function (element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },

            // Rules for form validation
            rules: {
                name: {
                    required: true,
                    alphanumeric: true
                },
                description: {
                    required: true,
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });

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
                $.get('{{ route("source-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var source = data.source;

                        modal.find('.modal-title').text('Edit Source');
                        modal.find('input[name=source_id]').val(itemId);
                        modal.find('input[name=name]').val(source.name);
                        modal.find('textarea[name=description]').html(source.description);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Source');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-source')[0].reset();
                modal.find('input[name=source_id]').val('');
            }
        })

        $('#form-source').submit(function (e) {

            e.preventDefault();

            var data = {};
            data.source_id = $(this).find('[name=source_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.description = $(this).find('[name=description]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!$(this).valid()) return false;

            /*if(!data.name || !data.description){
                $('#form-source-alert').html('<div class="alert alert-danger"> You haven\'t filled in all required information </div>');
                return false;
            }*/

            var btn = $(this).find('[type=submit]');
            btn.attr('disabled', 'disabled');
            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    /*$('#form-review').find("input, textarea").val("");
                    $('#form-review-alert').html('<div class="alert alert-success">' + data.message + '</div>');
                    $('.starrr').find('.glyphicon-star').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    $('#meaning').html('');*/
                    location.href = data.url;
                }else{
                    btn.removeAttr('disabled');
                    $('#form-source-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    btn.removeAttr('disabled');
                    $('#form-source-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
            });
        })

    })
</script>