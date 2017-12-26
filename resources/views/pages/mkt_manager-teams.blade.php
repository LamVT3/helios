@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-toggle="modal"
                   data-target="#createTeamModal"><i
                            class="fa fa-plus fa-lg"></i> Create Team</a>
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
                                <div class="widget-body-toolbar">
                                    <strong>Source: </strong>
                                    <label class="select">
                                        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)">
                                            <option value="{{ route('team', ['id' => 'all']) }}"
                                                    {{ $id == 'all' ? 'selected' : '' }}><a
                                                        href="">All</a></option>
                                            @foreach($sources as $item)
                                                <option value="{{ route('team', ['id' => $item->id]) }}"
                                                        {{ $id == $item->id ? 'selected' : '' }}><a
                                                            href="">{{ $item->name }}</a></option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                                <table id="table_teams" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Team Name</th>
                                        <th>Source</th>
                                        <th>Team Description</th>
                                        <th>Team Members</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{ debug($teams) }}
                                    @foreach ($teams as $item)
                                        <tr id="team-{{ $item->id }}">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->source['source_name'] }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                @foreach($item->members as $m)
                                                <span class="label label-primary">{{ '@'.$m['username'] }}</span>
                                                    @endforeach
                                            </td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>
                                                {{--@permission('edit-review')--}}
                                                <a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#createTeamModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>

                                                {{--@endpermission--}}
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

                @include('components.form-create-source', ['type' => null])

        </div>
        <!-- END MAIN CONTENT -->

    </div>
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
            "order": [[0, "desc"]]
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */

        allMembers = {!! $allMembers !!}

        $('input[name=members]').selectize({
            delimiter: ',',
            persist: false,
            valueField: '_id',
            labelField: 'name',
            searchField: ['name'],
            options: allMembers
        });
    })

</script>
@include('components.script-team')
@stop
