@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @include('layouts.errors')

        <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">

                    <!-- NEW WIDGET START -->
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        @component('components.jarviswidget',
                            ['id' => 0, 'icon' => 'fa-table', 'title' => 'History'])
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th data-hide="phone">Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($success_phone as $item)
                                        <tr>
                                            <td>{{ $item }}</td>
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
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
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
                "order": [[0, "desc"]],
                "iDisplayLength": page_size,
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
            });

            /* END BASIC */
        })

    </script>
@stop