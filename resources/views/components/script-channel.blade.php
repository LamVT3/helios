<script>
    $(function(){
        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
            var modal = $(this);
            modal.find('input[name=name]').removeAttr('disabled');
            if (itemId) {
                $.get('{{ route("channel-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var channel = data.channel;

                        modal.find('.modal-title').text('Edit Channel');
                        modal.find('input[name=channel_id]').val(itemId);
                        modal.find('input[name=name]').val(channel.name);
                        modal.find('input[name=name]').attr('disabled','disabled');
                        modal.find('input[name=fb_id]').val(channel.fb_id);
                        modal.find('textarea[name=thankyou_page]').html(channel.thankyou_page);
                        modal.find('select[name=is_active]').val(channel.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Channel');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=thankyou_page]').html('');
                $('#form-channel')[0].reset();
                modal.find('input[name=channel_id]').val('');
            }
        })

        var errorClass = 'invalid';
        var errorElement = 'em';

        $.validator.addMethod( "alphanumeric", function( value, element ) {
            return this.optional( element ) || /^[\w./:]+$/i.test( value );
        }, "Letters, numbers, dots and underscores only please" );

        $('#form-channel').validate({
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
                    // alphanumeric: true
                },
                thankyou_page: {
                    required: true,
                    // alphanumeric: true
                },
                fb_id: {
                    required: true,
                    // alphanumeric: true
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });

        $('#form-channel').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.channel_id = $(this).find('[name=channel_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.fb_id = $(this).find('[name=fb_id]').val();
            data.thankyou_page = $(this).find('[name=thankyou_page]').val();
            data.is_active = $(this).find('[name=is_active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!$(this).valid()) return false;

            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    location.href = data.url;
                }else{
                    $('#form-channel-alert').html('<div class="alert alert-danger">'+ data +'</div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-channel-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
            });
        })

    })
</script>