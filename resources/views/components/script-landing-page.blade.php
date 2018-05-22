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
                $.get('{{ route("landing-page-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {
                        var landing_page = data.landing_page;

                        modal.find('.modal-title').text('Edit Landing Page');
                        modal.find('input[name=landing_page_id]').val(itemId);
                        modal.find('input[name=name]').val(landing_page.name);
                        modal.find('input[name=platform]').val(landing_page.platform);
                        modal.find('input[name=url]').val(landing_page.url);
                        modal.find('textarea[name=description]').html(landing_page.description);
                        modal.find('select[name=is_active]').val(landing_page.is_active);
                        modal.find('[type=submit]').html('Save');
                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Landing Page');
                modal.find('[type=submit]').html('Create');
                modal.find('textarea[name=description]').html('');
                $('#form-landing-page')[0].reset();
                modal.find('input[name=landing_page_id]').val('');
            }
        })

        var errorClass = 'invalid';
        var errorElement = 'em';

        $.validator.addMethod( "alphanumeric", function( value, element ) {
            return this.optional( element ) || /^[\w./:]+$/i.test( value );
        }, "Letters, numbers, dots and underscores only please" );

        $('#form-landing-page').validate({
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

        $('#form-landing-page').submit(function (e) {
            //console.log('run');
            e.preventDefault();
            var data = {};
            data.landing_page_id = $(this).find('[name=landing_page_id]').val();
            data.name = $(this).find('[name=name]').val();
            data.platform = $(this).find('[name=platform]').val();
            data.url = $(this).find('[name=url]').val();
            data.description = $(this).find('[name=description]').val();
            data.is_active = $(this).find('[name=is_active]').val();
            data._token = $(this).find('[name=_token]').val();

            if(!$(this).valid()) return false;

            $.post($(this).attr('action'), data, function (data) {
                if(data.type && data.type == 'success'){
                    /*$('#form-review').find("input, textarea").val("");
                    $('#form-review-alert').html('<div class="alert alert-success">' + data.message + '</div>');
                    $('.starrr').find('.glyphicon-star').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    $('#meaning').html('');*/
                    location.href = data.url;
                }else{
                    $('#form-landing-page-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
                }
            }).fail(
                function (err) {
                    console.log(err);
                    $('#form-landing-page-alert').html('<div class="alert alert-danger"> Cannot connect to server. Please try again later. </div>');
            });
        })

    })
</script>