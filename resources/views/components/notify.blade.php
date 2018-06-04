@if(isset($notification) && empty($notification->users[auth()->user()->_id]))
<div class="modal fade" id="notify" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h3 class="modal-title"> Notify</h3>
            </div>
            <div class="modal-body">
                <div class="smart-form">
                    <fieldset>
                        <div class="row" style="margin: 10px -15px">
                            <section>
                                <h3 class="text-center">{{$notification->title}}</h3>
                                <br>
                                <div>
                                    {!! $notification->content !!}
                                </div>
                            </section>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" data-url="{{ route("notification-confirm", $notification->_id) }}" class="btn btn-primary" id="notify_confirm">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(window).on('load',function(){
        $('#notify').modal('show');

        $('#notify_confirm').click(function () {
            $.get($(this).data('url'), {}, function (data) {
                console.log(data);
                if (data.type && data.type == 'success') {
                    alert("Ok")
                    $('#notify').modal('hide');
                } else {
                    $('#notify').modal('hide');
                }
            })
        })
    });
</script>
@endif