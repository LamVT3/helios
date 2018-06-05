<script>
    $(function(){
        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var itemId = button.data('item-id')
            var modal = $(this);
            modal.find('input[name=name]').removeAttr('disabled');
            if (itemId) {
                $.get('{{ route("thankyou-page-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var thankyou_page = data.thankyou_page;

                        modal.find('.modal-title').text('Edit Thank You Page');
                        modal.find('input[name=thankyou_page_id]').val(itemId);
                        modal.find('input[name=name]').val(thankyou_page.name);
                        modal.find('input[name=name]').attr('disabled','disabled');
                        modal.find('input[name=url]').val(thankyou_page.url);
                        modal.find('textarea[name=description]').html(thankyou_page.description);
                        modal.find('select[name=is_active]').val(thankyou_page.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Thank You Page');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-thankyou-page')[0].reset();
                modal.find('input[name=thankyou_page_id]').val('');
            }
        })

        var errorClass = 'invalid';
        var errorElement = 'em';

        $.validator.addMethod( "alphanumeric", function( value, element ) {
            return this.optional( element ) || /^[\w./:]+$/i.test( value );
        }, "Letters, numbers, dots and underscores only please" );

        $('#form-thankyou-page').validate({
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
                url: {
                    required: true,
                    // alphanumeric: true
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });

        $('#form-thankyou-page').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.thankyou_page_id = $(this).find('[name=thankyou_page_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.url = $(this).find('[name=url]').val();
            data.description = $(this).find('[name=description]').val();
            data.is_active = $(this).find('[name=is_active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!$(this).valid()) return false;

            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    location.href = data.url;
                }else{
                    $('#form-thankyou-page-alert').html('<div class="alert alert-danger">'+ data +'</div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-thankyou-page-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
            });
        })

    })
</script>