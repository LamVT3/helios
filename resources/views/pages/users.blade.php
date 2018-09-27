@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                @if(auth()->user()->role == "Manager" || auth()->user()->role == "Admin")
                <!-- 2018-04-04 lamvt change button color -->
                <a href="{{ route('users-create') }}"
                   class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"><i
                            class="fa fa-plus fa-lg"></i> Create User</a>
                <!-- end 2018-04-04 -->
                @endif
            @endcomponent

        @include('layouts.errors')

        <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">

                    <!-- NEW WIDGET START -->
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        @component('components.jarviswidget',
                            ['id' => 0, 'icon' => 'fa-table', 'title' => 'User List'])
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th data-hide="phone">ID</th>
                                        <th data-class="expand">
                                            Username
                                        </th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <!-- 2018-04-04 lamvt add active column -->
                                        <th>Active</th>
                                        <!-- end 2018-04-04 -->
                                        <th data-hide="phone,tablet"><i
                                                    class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                            Created at
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($users as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td><a href="{{ route('profile-user', $item->username) }}">{{ $item->username }}</a></td>
                                            <td>{{ $item->role }}</td>
                                            <td>{{ $item->email }}</td>
                                            <!-- 2018-04-04 lamvt add active column -->
                                            @if($item->is_active == 1)
                                                <td style="color: #00a300;">Active</td>
                                            @else
                                                <td style="color: red;">In-Active</td>
                                            @endif
                                            <!-- end 2018-04-04 -->
                                            <td>{{ $item->created_at->toFormattedDateString() }}</td>
                                            <td>
                                                @if(auth()->user()->role == "Manager" || auth()->user()->role == "Admin")
                                                <a class='btn btn-xs btn-default'
                                                   href="{{ route('users-edit', ['id' => $item->id]) }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>
                                                @endif
                                                <a class="name btn btn-default btn-xs"
                                                   href="{{ route('profile-user', $item->username) }}" target="_blank"><i
                                                            class='fa fa-eye'></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </article>
                    <!-- WIDGET END -->

                </div>

                <!-- end row -->

                <!-- end row -->

            </section>
            <!-- end widget grid -->

            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title"> Bạn có chắc chắn xóa user này?</h3>
                        </div>
                        <div class="modal-footer">
                            <form method="post" action="{{ route('delete', ['model' => 'User']) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value=""/>
                                <button type="submit" class="btn btn-danger">
                                    Xóa
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>

                            </form>
                        </div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

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

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function () {
            var page_size       = $('input[name="page_size"]').val();
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var itemName = button.data('item-name') // Extract info from data-* attributes
                var itemId = button.data('item-id')
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this)
                modal.find('.modal-title').text('Bạn có chắc chắn xóa user "' + itemName + '"?')
                modal.find('input[name=id]').val(itemId)
            })

            /* BASIC ;*/
            var responsiveHelper_dt_basic = undefined;


            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#dt_basic').dataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12'i><'col-xs-12 col-sm-6'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_dt_basic) {
                        responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_dt_basic.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_dt_basic.respond();
                },
                "order": [[4, "desc"]],
                "iDisplayLength": parseInt(page_size),
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
            });

            /* END BASIC */
        })

    </script>
@stop