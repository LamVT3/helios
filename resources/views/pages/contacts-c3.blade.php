@extends('layouts.master')

@section('content')
<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
        <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
           data-toggle="modal"
           data-target="#addModal"><i
                    class="fa fa-upload fa-lg"></i> Import Contact</a>
        @endcomponent

        @include('layouts.errors')

        <!-- widget grid -->
        <section id="widget-grid" class="">

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
                                    <a id="filter" href="javascript:void(0)"><i class="fa fa-angle-down fa-lg"></i></a>
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
                                            data-url="">
                                        <option value="">All</option>
                                        @foreach($subcampaigns as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                            <option value="c3a">C3A</option>
                                            <option value="c3b" selected>C3B</option>
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-2">
                                        <label class="label">CRM Level</label>
                                        <select name="current_level" id="current_level" class="select2"
                                                style="width: 280px">
                                            <option value="">All</option>
                                            <option value="l1">L1</option>
                                            <option value="l2">L2</option>
                                            <option value="l3">L3</option>
                                            <option value="l4">L4</option>
                                            <option value="l5">L5</option>
                                            <option value="l6">L6</option>
                                            <option value="l7">L7</option>
                                            <option value="l8">L8</option>
                                        </select>
                                        <i></i>
                                    </section>
                                </div>
                            </fieldset>


                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                        <i class="fa fa-filter"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>

                        </form>
                        <div style="position: relative">
                            <form action="{{ route('contacts.export')}}" enctype="multipart/form-data">
                                <input type="hidden" name="source_id">
                                <input type="hidden" name="marketer_id">
                                <input type="hidden" name="campaign_id">
                                <input type="hidden" name="team_id">
                                <input type="hidden" name="clevel">
                                <input type="hidden" name="current_level">
                                <input type="hidden" name="registered_date">
                                <input type="hidden" name="page_size" value="{{$page_size}}">
                                <div style="position: absolute; right: 90px; bottom: 0px;">
                                    <button class="btn btn-success" type="submit"
                                            style=""> <i class="fa fa-download"></i> Export
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="loading" style="display: none">
                            <div class="col-md-12 text-center">
                                <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                            </div>
                        </div>
                        <hr>
                        <div class="container-table-contacts">
                        <div class="wrapper">
                            <table id="table_contacts" class="table table-striped table-bordered table-hover"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Age</th>
                                        <th>Registered at</th>
                                        <th>C Level</th>
                                        <th>CRM level</th>
                                        <th>Source</th>
                                        <th>Team</th>
                                        <th>Marketer</th>
                                        <th>Campaign</th>
                                        <th>Subcampaign</th>
                                        <th>Ads</th>
                                        <th>Landing page</th>
                                        <th>Action</th>
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
