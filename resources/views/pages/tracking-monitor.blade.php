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
                            <div class="widget-body">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th data-hide="phone">Time run</th>
                                        <th>Total Success</th>
                                        <th>Total Duplicate</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($jobs as $item)
                                        <tr>
                                            <td>{{ $item['date'] }}</td>
                                            <td>{{ $item['total_success'] }}</td>
                                            <td>{{ $item['total_duplicate'] }}</td>
                                            <td><a href="{{route('tracking-inventory-show', $item['_id'])}}">Detail</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-right">{{ $jobs->links() }}</div>
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

    </script>
@stop