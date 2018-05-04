@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                @if(auth()->user()->role == "Manager")
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-toggle="modal"
                   data-target="#createTeamModal"><i
                            class="fa fa-plus fa-lg"></i> Create Team</a>
                @endif
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Teams (' . $teams->count() . ')'])
                            <div class="widget-body no-padding">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="close" data-dismiss="alert">
                                        ×
                                    </button>
                                    <i class="fa-fw fa fa-info"></i>
                                    A 'team' is a marketing team or an advertising account. A team should be created by a marketing manager. It will be shown as <span class="txt-color-orangeDark">utm_team</span> in a tracking link.
                                </div>
                                <table id="table_teams" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Team Name</th>
                                        <th>Team Description</th>
                                        <th>Team Members</th>
                                        <th>Sources</th>
                                        <th>Creator</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($teams as $item)
                                        <tr id="team-{{ $item->id }}">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                @if($item->members)
                                                    @foreach($item->members as $m)
                                                        <span class="label label-primary">{{ '@'.$m['username'] }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->sources)
                                                    @foreach($item->sources as $s)
                                                        <span class="label label-warning">{{ $s['source_name'] }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $item->creator_name or '' }}</td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>
                                                @if(auth()->user()->role == "Manager")
                                                <a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#createTeamModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>

                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </article>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

                @include('components.form-create-team', ['type' => null])

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

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        var page_size       = $('input[name="page_size"]').val();

        /* BASIC ;*/
        var responsiveHelper_table_team = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_teams').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_team) {
                    responsiveHelper_table_team = new ResponsiveDatatablesHelper($('#table_teams'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_team.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_team.respond();
            },
            "order": [],
            "iDisplayLength": page_size,
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */

        allMembers = {!! $allMembers !!}

        $('input[name=members]').selectize({
            delimiter: ',',
            persist: false,
            valueField: '_id',
            labelField: 'username',
            searchField: ['username'],
            options: allMembers
        });

        allSources = {!! $allSources !!}

        $('input[name=sources]').selectize({
            delimiter: ',',
            persist: false,
            valueField: '_id',
            labelField: 'name',
            searchField: ['name'],
            options: allSources
        });
    })

</script>
@include('components.script-team')
@stop
