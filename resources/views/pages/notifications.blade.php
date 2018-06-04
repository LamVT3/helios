@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   id="btn-create"
                   data-item-type="Campaign"
                   data-toggle="modal"
                   data-target="#addModal"><i
                            class="fa fa-plus fa-lg"></i> Create Notification</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">
                    @if(!isset($type) || $type != 'show')
                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Notification (' . $notifications->count() . ')'])
                            <div class="widget-body no-padding">
                                <table id="table_notification" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Created at</th>
                                        <th>Creator</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($notifications as $item)
                                        <tr id="notify-{{ $item->id }}">
                                            <td>{{ $item->title }}</td>
                                            <td><p>{!! substr($item->content, 0,30) !!}...</p></td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>
                                                @if(auth()->user()->role == "Admin")
                                                    <a data-toggle="modal" class='btn btn-xs btn-default'
                                                       data-target="#addModal"
                                                       data-item-id="{{ $item->id }}"
                                                       data-original-title='Edit Row'><i
                                                                class='fa fa-pencil'></i></a>
                                                @endif
                                                <a class="name btn btn-default btn-xs"
                                                   href="{{ route('notification-show', $item->_id) }}"><i
                                                            class='fa fa-eye'></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </article>
                    @else
                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'User ('.count($notification->users).')'])
                            <div class="widget-body no-padding">
                                <table id="table_notification" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($notification->users) > 0)
                                    @foreach ($notification->users as $item)
                                        <tr>
                                            <td>{{ $item['username'] }}</td>
                                            <td>{{ $item['date'] }}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </article>
                    @endif
                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

                @include('components.form-create-notification', ['type' => null])

        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <input type="hidden" name="page_size" value="{{$page_size}}">
    <!-- END MAIN PANEL -->

@endsection


@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
<script src="{{ asset('js/plugin/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        var page_size   = $('input[name="page_size"]').val();

        /* BASIC ;*/
        var responsiveHelper_table_campaign = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_notification').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_campaign) {
                    responsiveHelper_table_campaign = new ResponsiveDatatablesHelper($('#table_notification'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_campaign.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_campaign.respond();
            },
            "order": [],
            "iDisplayLength": page_size,
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
        });

        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var itemId = button.data('item-id');
            var modal = $(this);
            modal.find('textarea[name=content]').html('');
            modal.find('input[name=title]').val('');
            modal.find('input[name=notification_id]').val('');
            modal.find('textarea[name=content]').html('');
            if (itemId) {
                $.get('{{ route("notification-get", "") }}/' + itemId, {}, function (data) {
                    if (data.type && data.type == 'success') {

                        var notification = data.notification;
                        modal.find('.modal-title').text('Edit Notification');
                        modal.find('input[name=notification_id]').val(itemId);
                        modal.find('input[name=notification_type]').val('Edit');
                        modal.find('input[name=title]').val(notification.title);
                        $('.textarea').html('');
                        $('.textarea').append('<textarea name="content" id="textarea_content" required></textarea>');
                        modal.find('textarea[name=content]').html(notification.content);
                        modal.find('[type=submit]').html('Save');
                        tinymce.init({
                            selector: '#textarea_content',
                            height: 200,
                            menubar: false,
                            plugins: [
                                'advlist autolink lists link image charmap print preview anchor textcolor',
                                'searchreplace visualblocks code fullscreen',
                                'insertdatetime media table contextmenu paste code help wordcount'
                            ],
                            toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                            content_css: [
                                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                                '//www.tinymce.com/css/codepen.min.css']
                        });

                    } else {
                        modal.close();
                    }
                })
            }else{
                modal.find('.modal-title').text('Create Notification');
                modal.find('[type=submit]').html('Create');
            }
        });

        $('#btn-create').click(function () {
            $('.textarea').html('');
            $('.textarea').append('<textarea name="content" id="textarea_content" required></textarea>');
            tinymce.init({
                selector: '#textarea_content',
                height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor textcolor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code help wordcount'
                ],
                toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });
        });



    })

</script>
@stop
