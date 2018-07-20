@extends('layouts.master')

@section('content')
<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'currency' => true])
            <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
               data-toggle="modal"
               data-target="#addModal"><i
                        class="fa fa-upload fa-lg"></i> Import Contact</a>

            <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
               data-toggle="modal"
               data-target="#eGenticModal" style="margin-right: 10px;"><i
                        class="fa fa-upload fa-lg"></i> Import eGentic</a>
        @endcomponent

        @include('layouts.errors')

        <!-- widget grid -->
        <section id="widget-grid" class="">
            <div id="export_success" class="alert alert-block alert-success" style="display: none">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <p>
                    Contacts has been exported successfully.
                </p>
            </div>
            <div id="update_success" class="alert alert-block alert-success" style="display: none">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <p>
                    Contacts has been updated successfully.
                </p>
            </div>
            <!-- row -->

            <div class="row">

                <article class="col-sm-12 col-md-12">

                    @component('components.jarviswidget',
                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'C3 Produced'])
                    <div class="widget-body">
                        <form id="search-form-c3" class="smart-form" action="#" url="{!! route('ajax-paginate') !!}">
                            <div class="row">
                                <div id="reportrange" class="pull-left"
                                     style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span class="registered_date"></span> <b class="caret"></b>
                                </div>
                            </div>
                            <fieldset>
                                <legend>Filter
                                    <a id="filter" href="javascript:void(0)"><i class="fa fa-angle-up fa-lg"></i></a>
                                </legend>
                                <div class="row" id="filter">
                                    <section class="col col-2">
                                        <label class="label">Source</label>
                                        <select name="source_id" class="select2" style="width: 280px" id="source_id"
                                                data-url="{!! route('ajax-getFilterSource') !!}">
                                            <option value="">All</option>
                                            @foreach($sources as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Team</label>
                                        <select name="team_id" class="select2" style="width: 280px" id="team_id"
                                                data-url="{!! route('ajax-getFilterTeam') !!}">
                                            <option value="">All</option>
                                            @foreach($teams as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Marketer</label>
                                        <select name="marketer_id" id="marketer_id" class="select2" style="width: 280px"
                                                data-url="{!! route('ajax-getFilterMaketer') !!}">
                                            <option value="">All</option>
                                            @foreach($marketers as $item)
                                                <option value="{{ $item->id }}">{{ $item->username }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Campaign</label>
                                        <select name="campaign_id" id="campaign_id" class="select2" style="width: 280px"
                                                data-url="{!! route('ajax-getFilterCampaign') !!}">
                                            <option value="">All</option>
                                            @foreach($campaigns as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Sub Campaign</label>
                                        <select name="subcampaign_id" id="subcampaign_id" class="select2" style="width: 280px"
                                                data-url="{!! route('ajax-getFilterSubCampaign') !!}">
                                            <option value="">All</option>
                                            @foreach($subcampaigns as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Channel</label>
                                        <select name="channel_id" id="channel_id" class="select2" style="width: 280px"
                                                data-url="">
                                            <option value="">All</option>
                                            @foreach($channel as $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                </div>
                                <div class="row" id="filter">
                                    <section class="col col-2">
                                        <label class="label">CLevel</label>
                                        <select name="clevel" id="clevel" class="select2"
                                                style="width: 280px">
                                            <option value="">All</option>
                                            <option value="c3a">c3a</option>
                                            <option value="c3b" selected>c3b</option>
                                            <option value="c3b_only">c3b Only</option>
                                            <option value="c3bg">c3bg</option>
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">CRM Level</label>
                                        <select name="current_level" id="current_level" class="select2"
                                                style="width: 280px">
                                            <option value="">All</option>
                                            <option value="l0">l0</option>
                                            <option value="l1">l1</option>
                                            <option value="l2">l2</option>
                                            <option value="l3">l3</option>
                                            <option value="l4">l4</option>
                                            <option value="l5">l5</option>
                                            <option value="l6">l6</option>
                                            <option value="l7">l7</option>
                                            <option value="l8">l8</option>
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Landing Page</label>
                                        <select name="landing_page" id="landing_page" class="select2" style="width: 280px"
                                                data-url="">
                                            <option value="">All</option>
                                            @foreach($landing_page as $item)
                                                <option value="{{ $item->url }}">{{ $item->url }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Status Export</label>
                                        <select name="is_export" id="is_export" class="select2"
                                                style="width: 280px" data-url="">
                                            <option value="">All</option>
                                            <option value="1">Exported</option>
                                            <option value="0">Not Export</option>
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">Status OLM</label>
                                        <select name="olm_status" id="olm_status" class="select2"
                                                style="width: 280px" data-url="">
                                            <option value="">All</option>
                                            <option value="0">Success</option>
                                            <option value="1">Duplicated</option>
                                            <option value="2">Error</option>
                                            <option value="3">Not Export</option>
                                        </select>
                                        <i></i>
                                    </section>
                                </div>
                                <div class="row" id="filter">
                                    <section class="col col-2" style="margin: 0px;">
                                        <label class="label">Export</label>
                                    </section>
                                </div>
                                <div class="row" id="filter">
                                    <section class="col col-4">
                                        <label class="checkbox">
                                            <input type="checkbox" id="mark_exported"/>
                                            <i></i>Mark contact as “Exported”</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" id="limit" type="number"
                                                   placeholder="Export..." max="" min="1" data-placement="bottom" data-toggle="tooltip" title="Enter number to export...">
                                        </div>
                                        <div class="col-xs-4 export_label" id="">
                                            <label class="export_label" for="limit"> entries</label>
                                        </div>
                                    </section>
                                    {{--<section class="col col-3">--}}
                                        {{--<label class="checkbox">--}}
                                            {{--<input type="checkbox" id="c3bg" name="c3bg"/>--}}
                                            {{--<i></i>C3BG</label>--}}
                                        {{--<div id="c3range" class="pull-left"--}}
                                             {{--style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; /*margin: 10px 15px*/">--}}
                                            {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;--}}
                                            {{--<span class="checked_date"></span> <b class="caret"></b>--}}
                                        {{--</div>--}}
                                    {{--</section>--}}
                                </div>
                                <div class="row" id="filter">
                                    <section class="col col-12 pull-right text-right">
                                        <button id="export" class="btn btn-success btn-sm" type="button" style="margin-left: 10px"
                                                data-toggle="modal" data-target="#myExportModal"> <i class="fa fa-download"></i>
                                            Export to excel
                                        </button>
                                        <button id="export_to_olm" class="btn btn-danger btn-sm" type="button"
                                                style="margin-left: 10px" data-toggle="modal" data-target="#myExportToOLMModal">
                                            <i class="fa fa-edit"></i>
                                            Export to OLM
                                        </button>
                                        <button id="update_contact" class="btn btn-warning btn-sm" type="button"
                                                style="margin-left: 10px; display: none" data-toggle="modal" data-target="#myUpdateModal">
                                            <i class="fa fa-edit"></i>
                                            Update
                                        </button>
                                        <button id="edit_contact" class="btn btn-warning btn-sm disabled" type="button" disabled
                                                style="margin-left: 10px;">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </button>
                                        <button id="filter" class="btn btn-primary btn-sm" type="submit" style="margin-left: 10px" >
                                            <i class="fa fa-filter"></i>
                                            Filter
                                        </button>
                                    </section>
                                </div>
                            </fieldset>

                        </form>

                        <div style="position: relative">
                            <form id="export-form-c3" action="{{ route('contacts.export')}}" enctype="multipart/form-data">
                                <input type="hidden" name="source_id">
                                <input type="hidden" name="marketer_id">
                                <input type="hidden" name="campaign_id">
                                <input type="hidden" name="team_id">
                                <input type="hidden" name="clevel">
                                <input type="hidden" name="current_level">
                                <input type="hidden" name="registered_date">
                                <input type="hidden" name="limit">
                                <input type="hidden" name="mark_exported" value="0">
                                <input type="hidden" name="status">
                                <input type="hidden" name="landing_page">
                                <input type="hidden" name="channel">
                                <input type="hidden" name="search_text" value="">
                                <input type="hidden" name="olm_status" value="">
                                <input type="hidden" name="contact_id" value="">
                            </form>
                        </div>

                        <div class="loading" style="display: none">
                            <div class="col-md-12 text-center">
                                <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                            </div>
                        </div>
                        <hr>
                        <div style="padding-left: 20px">
                            <p id="cnt_exported" class="text-success no-margin"><strong>...</strong></p>
                            <p id="cnt_export_to_olm" class="text-primary no-margin"><strong>...</strong></p>
                        </div>
                        <div class="container-table-contacts">
                        <div class="wrapper">
                            <table id="table_contacts" class="table table-striped table-bordered table-hover"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="update_all" value="all"/></th>
                                        <th class="long">Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Age</th>
                                        <th class="long">Registered at</th>
                                        <th class="long">C Level</th>
                                        <th class="long">CRM level</th>
                                        <th>Ads</th>
                                        <th>Channel</th>
                                        <th>Source</th>
                                        <th>Team</th>
                                        <th>Marketer</th>
                                        <th>Campaign</th>
                                        <th>Subcampaign</th>
                                        <th class="long">Landing page</th>
                                        <th>Action</th>
                                        <th class="long">Status export</th>
                                        <th class="long">Status OLM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{--@foreach ($contacts as $item)--}}
                                    {{--<tr id="contact-{{ $item->_id }}">--}}
                                        {{--<td><a href="javascript:void(0)" class="name" data-id="{{ $item->_id }}">{{ $item->name }}</a>--}}
                                        {{--</td>--}}
                                        {{--<td>{{ $item->email }}</td>--}}
                                        {{--<td>{{ $item->phone }}</td>--}}
                                        {{--<td>{{ $item->age }}</td>--}}
                                        {{--<td>{{ Date('d-m-Y H:i:s', $item->submit_time/1000) }}</td>--}}
                                        {{--<td>{{ $item->clevel }}</td>--}}
                                        {{--<td>{{ $item->current_level }}</td>--}}
                                        {{--<td>{{ $item->source_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->team_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->marketer_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->campaign_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->subcampaign_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->ad_name or '-100' }}</td>--}}
                                        {{--<td>{{ $item->landing_page }}</td>--}}
                                        {{--<td>--}}
                                            {{--@permission('edit-review')--}}
                                            {{--<a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="{{ $item->_id }}"><i--}}
                                                        {{--class='fa fa-eye'></i></a>--}}
                                            {{--<a data-toggle="modal" class='btn btn-xs btn-default'--}}
                                                   {{--data-target="#deleteModal"--}}
                                                   {{--data-item-id="{{ $item->_id }}"--}}
                                                   {{--data-item-name="{{ $item->name }}"--}}
                                                   {{--data-original-title='Delete Row'><i--}}
                                                        {{--class='fa fa-times'></i></a>--}}
                                            {{--@endpermission--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--@endforeach--}}

                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    @endcomponent

                </article>

            </div>

            <!-- end row -->

        </section>
        <!-- end widget grid -->

        @include('components.form-import-contact', ['type' => null])
        @include('components.form-import-egentic', ['type' => null])

        {{--
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h3 class="modal-title"> Are you sure you want to delete this campaign?</h3>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value=""/>
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancel
                            </button>

                        </form>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->
        </div>
        --}}

    </div>
    <!-- END MAIN CONTENT -->

</div>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h3 class="modal-title"><i class="fa fa-table"></i> Contact Details</h3>
            </div>
            <div class="modal-body">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Export Modal -->
<div class="modal fade" id="myExportModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirm Export</h3>
            </div>
            <div class="modal-body">
                <h4>{{config('constants.CONFIRM_EXPORT')}}</h4>
            </div>
            <div class="modal-footer">
                <button id="confirm_export" type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>

<!-- Export To OLM Modal -->
<div class="modal fade" id="myExportToOLMModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirm Export To OLM</h3>
            </div>
            <div class="modal-body">
                <h4>{{config('constants.CONFIRM_EXPORT')}}</h4>
            </div>
            <div class="modal-footer">
                <button id="confirm_export_to_olm" type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>

<input type="hidden" name="page_size" value="{{$page_size}}">
<input type="hidden" name="exported" value="{{$export_to_excel}}">
<input type="hidden" name="export_to_olm" value="{{$export_to_olm}}">
<input type="hidden" name="exported_url" value="{{route("contacts.countExported")}}">
<input type="hidden" name="update_status_export" value="{{route("ajax-updateStatusExport")}}">
<input type="hidden" name="export_to_olm_url" value="{{route("contacts.export-to-OLM")}}">
<input type="hidden" name="update_all" value="0">
<input type="hidden" name="status_update_all" value="">
@endsection

@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/contacts/contacts_c3.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

<script type="text/javascript" src="//cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css"/>


<script type="text/javascript">
    $(document).ready(function(){
        $('.container-table-contacts').on('click', '.name', (function(){
            var id = $(this).data('id');
            var loading = '<p class="text-center">' +
                '                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 5%;"/>' +
                '                </p>';
            $('#contactModal .modal-body').html(loading);
            $('#contactModal').modal('show');
            $.get("{{ route('contact-details', '') }}/" + id, function (data) {
                $('#contactModal .modal-body').html(data);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }));
    });

    $(document).ready(function () {
        $('.loading').show();
        initDataTable();
        setTimeout("$('.loading').hide();", 1000);
    });

</script>

@stop
