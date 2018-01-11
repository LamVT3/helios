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
                    class="fa fa-plus fa-lg"></i> Create Contact</a>
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
                        <form id="search-form-c3" class="smart-form" action="#" url="{!! route('contacts.filter') !!}">
                            <div class="row">
                                <div id="reportrange" class="pull-left"
                                     style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span class="registered_date"></span> <b class="caret"></b>
                                </div>
                            </div>
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">Source</label>
                                    <label class="select"> <i class="icon-append fa fa-user"></i>
                                        <select name="source_id">
                                            <option value="">All</option>
                                            @foreach($sources as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Team</label>
                                    <label class="select"> <i class="icon-append fa fa-user"></i>
                                        <select name="team_id">
                                            <option value="">All</option>
                                            @foreach($teams as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Marketer</label>
                                    <label class="select"> <i class="icon-append fa fa-user"></i>
                                        <select name="marketer_id">
                                            <option value="">All</option>
                                            @foreach($marketers as $item)
                                            <option value="{{ $item->id }}">{{ $item->username }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Campaign</label>
                                    <label class="select"> <i class="icon-append fa fa-user"></i>
                                        <select name="campaign_id">
                                            <option value="">All</option>
                                            @foreach($campaigns as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Current Level</label>
                                    <label class="select"> <i class="icon-append fa fa-user"></i>
                                        <select name="current_level">
                                            <option value="">All</option>
                                            <option value="1">L1</option>
                                            <option value="2">L2</option>
                                            <option value="3">L3</option>
                                            <option value="4">L4</option>
                                            <option value="5">L5</option>
                                            <option value="6">L6</option>
                                            <option value="7">L7</option>
                                            <option value="8">L8</option>
                                        </select>
                                        <i></i>
                                    </label>
                                </section>

                            </div>
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
                                <input type="hidden" name="current_level">
                                <input type="hidden" name="registered_date">
                                <div style="position: absolute; right: 75px; bottom: 0px;">
                                    <button class="btn btn-success" type="submit"
                                            style="background-color: #3276b1;border-color: #2c699d;">Export Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="wrapper">
                            <table id="table_campaigns" class="table table-striped table-bordered table-hover"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Registered at</th>
                                        <th>Current level</th>
                                        <th>Marketer</th>
                                        <th>Campaign</th>
                                        <th>Channel</th>
                                        <th>Ads</th>
                                        <th>Landing page</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $item)
                                    <tr id="contact-{{ $item->id }}">
                                        <td><a href="{{ route("contacts-details", $item->id) }}">{{ $item->name }}</a>
                                        </td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ Date('d-m-Y H:i:s', strtotime($item->registered_date)) }}</td>
                                        <td>{{ $item->current_level }}</td>
                                        <td>{{ $item->marketer_name }}</td>
                                        <td>{{ $item->campaign_name }}</td>
                                        <td>{{ $item->subcampaign_name }}</td>
                                        <td>{{ $item->ad_name }}</td>
                                        <td>{{ $item->landingpage_name }}</td>
                                        <td>
                                            {{--@permission('edit-review')--}}
                                            <a data-toggle="modal" class='btn btn-xs btn-default'
                                               data-target="#addModal"
                                               data-item-id="{{ $item->id }}"
                                               data-original-title='Edit Row'><i
                                                        class='fa fa-pencil'></i></a>
                                            {{--<a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#deleteModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-item-name="{{ $item->name }}"
                                                   data-original-title='Delete Row'><i
                                                        class='fa fa-times'></i></a>--}}
                                            {{--@endpermission--}}
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endcomponent

                </article>

            </div>

            <!-- end row -->

        </section>
        <!-- end widget grid -->

        {{--@include('components.form-create-campaign', ['type' => null])--}}

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
<!-- END MAIN PANEL -->
<div class="modal fade" id="modal_gif" role="dialog">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content text-center" style="background: #f7f7f7;border-radius: 0px;">
            <h4>Loading...</h4>
            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 10%;"/>
        </div>
    </div>
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
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
<script type="text/javascript">



</script>
@include('components.script-campaign')
@stop
