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
                                        <th data-hide="phone">Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($phone_paged as $item)
                                        <tr>
                                            <td>{{ $item }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-right">{{ $phone_paged->links() }}</div>
                            </div>
                        @endcomponent

                    </article>
                    <!-- WIDGET END -->

                </div>

            </section>
            <!-- end widget grid -->

        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <input type="hidden" name="page_size" value="5">
    <!-- END MAIN PANEL -->
@endsection

@section('script')
    <!-- PAGE RELATED PLUGIN(S) -->
    <script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

@stop