@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'currency' => true])
            @endcomponent

            @include('layouts.errors')

        <!-- widget grid -->
            <section id="widget-grid" class="">
                <div id="loader" class="loader" style="display: none"></div>
                <div id="import_success" class="alert alert-block alert-success" style="display: none">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    <p id="import_text">
                        Contacts has been imported successfully.
                    </p>
                </div>

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
                                <form id="search-form-c3" class="smart-form" action="#" url="{!! route('contacts-paginate') !!}">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
                                                <label class="label">Channel</label>
                                                <input style="190px !impotant" type="text" value="" name="channel_id" id="channel_id" placeholder="Select channel">
                                            <!-- <select name="channel_id" id="channel_id" class="select2" style="width: 280px"
                                                data-url="">
                                            <option value="">All</option>
                                            @foreach($channel as $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                                    </select> -->
                                                <i></i>
                                            </section>
                                        </div>
                                        <div class="row" id="filter">
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-2 col-sm-4">
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
                                            <section class="col col-lg-4 col-sm-8">
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
                                            <section class="col col-lg-2 col-sm-4">
                                                <label class="label">Status Export</label>
                                                <select name="is_export" id="is_export" class="select2"
                                                        style="width: 280px" data-url="">
                                                    <option value="">All</option>
                                                    <option value="1">Exported</option>
                                                    <option value="0">Not Export</option>
                                                </select>
                                                <i></i>
                                            </section>
                                            <section class="col col-lg-2 col-sm-4">
                                                <label class="label">Status Sale</label>
                                                <select name="olm_status" id="olm_status" class="select2"
                                                        style="width: 280px" data-url="">
                                                    <option value="">All</option>
                                                    <option value="0">Success</option>
                                                    <option value="1">Duplicated</option>
                                                    <option value="2">Error</option>
                                                    <option value="-1">Not Export</option>
                                                </select>
                                                <i></i>
                                            </section>
                                        </div>
                                        <div class="row" id="filter">
                                            <section class="col col-lg-2 col-sm-4">
                                                <label class="label">Status Mailchimp</label>
                                                <select name="mailchimp_expired" id="mailchimp_expired" class="select2"
                                                        style="width: 280px" data-url="">
                                                    <option value="">All</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <i></i>
                                            </section>
                                            <section class="col col-lg-2 col-sm-4">
                                                <label class="label">Tranfer Date</label>
                                                <div id="tranfer_date" class="pull-left"
                                                     style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; min-width: 170px">
                                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                    <span class="tranfer_date_span"></span> <b class="caret"></b>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="row" id="filter">
                                            <section class="col col-12 pull-right text-right">
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
                                        <input type="hidden" name="tranfer_date" value="">
                                        <input type="hidden" name="mailchimp_expired" value="">
                                    </form>
                                </div>

                                <div class="loading" style="display: none">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                    </div>
                                </div>
                                <hr style="padding: 10px">
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
                                                <th>c3_label1_origin</th>
                                                <th>c3_label2_origin</th>
                                                <th>c3_label3_origin</th>
                                                <th>c3_label4_origin</th>
                                                <th>c3_label5_origin</th>
                                                <th>c3_label6_origin</th>
                                                <th>c3_label7_origin</th>
                                                <th>c3_label8_origin</th>
                                                <th>c3_label9_origin</th>
                                                <th>c3_label10_origin</th>
                                                <th>Age</th>
                                                <th class="long">Registered at</th>
                                                <th class="long">C Level</th>
                                                <th class="long">CRM level</th>
                                                <th class="channel_long">Ads</th>
                                                <th class="channel_long">Channel</th>
                                                <th>Source</th>
                                                <th>Team</th>
                                                <th>Marketer</th>
                                                <th>Campaign</th>
                                                <th>Subcampaign</th>
                                                <th class="long">Landing page</th>
                                                <th class="long">Invalid reason</th>
                                                <th>Action</th>
                                                <th class="long">Status export</th>
                                                <th class="long">Status Sale</th>
                                                <th class="long">Tranfer Date</th>
                                                <th class="long">Send SMS</th>
                                                <th class="long">Status Mailchimp</th>
                                            </tr>
                                            </thead>
                                            <tbody>
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
    <div class="modal fade" id="myExportToOLMModal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    <h3 class="modal-title">Export To Sales</h3>
                </div>
                <div class="modal-body">
                    <form class="smart-form" id="form-export-to-sale">
                        <div class="form-group">
                            <label for="export_sale_date" class="label">Export date</label>
                            <input type="text" class="form-control" id="export_sale_date" style="padding-left: 5px">
                        </div>
                        <div class="form-group" style="padding-top: 10px">
                            <label for="export_sale_limit" class="label require_field">Export number</label>
                            <input type="number" class="form-control" id="export_sale_limit" style="padding-left: 5px">
                            <em id="export_sale_limit-error" class="error_require_field" style="display: none;">This field is required.</em>
                        </div>
                        <div class="form-group" style="padding-top: 10px">
                            <label for="export_sale_sort" class="label require_field">Sort</label>
                            <input type="radio" class="form-check-input" id="export_sale_sort" name="export_sale_sort" value="desc" checked><span style="padding: 5px 15px 5px 5px">Descending</span>
                            <input type="radio" class="form-check-input" id="export_sale_sort" name="export_sale_sort" value="asc" style="padding-left: 5px"><span style="padding: 5px">Ascending</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="confirm_export_to_olm" type="button" class="btn btn-primary">Yes</button>
                    <button id="close_modal_export_to_olm" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <div class="loading_modal" style="display: none">
                        <div class="col-md-12 text-center">
                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 5%;"/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Send SMS Modal -->
    <div class="modal fade" id="mySendSMSModal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    <h3 class="modal-title">Send SMS</h3>
                </div>
                <div class="modal-body">
                    <form class="smart-form" id="form-send-sms">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <span style="color: #333; font-weight: bold; font-size: 13px;">Credit balance: </span>
                            <span id="standard_balance" class="form-control-static">0</span>
                        </div>
                        <div class="form-group">
                            <span style="color: #333; font-weight: bold; font-size: 13px;">Credit premium balance: </span>
                            <span id="premium_balance" class="form-control-static">0</span>
                        </div>
                        <div class="form-group">
                            <label for="send_sms_limit" class="label require_field">Send number</label>
                            <input type="number" class="form-control" id="send_sms_limit" style="padding-left: 5px">
                            <em id="send_sms_limit-error" class="error_require_field" style="display: none;">This field is required.</em>
                        </div>
                        <div class="form-group" style="padding-top: 10px">
                            <label for="send_sms_sort" class="label require_field">Sort</label>
                            <input type="radio" class="form-check-input" id="send_sms_sort" name="send_sms_sort" value="desc" checked><span style="padding: 5px 15px 5px 5px">Descending</span>
                            <input type="radio" class="form-check-input" id="send_sms_sort" name="send_sms_sort" value="asc" style="padding-left: 5px"><span style="padding: 5px">Ascending</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="confirm_send_sms" type="button" class="btn btn-primary">Yes</button>
                    <button id="close_modal_send_sms" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <div class="loading_modal" style="display: none">
                        <div class="col-md-12 text-center">
                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 5%;"/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Send SMS Result Modal -->
    <div class="modal fade" id="mySendSMSResultModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Send SMS Result</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size: 25px" id="total_send">...</p>
                    <p class="text-primary" style="font-size: 25px" id="send_pass">...</p>
                    <p class="text-danger" style="font-size: 25px" id="send_fail">...</p>
                    <p class="text-warning" style="font-size: 25px" id="used_credit">...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Count Export To OLM Modal -->
    <div class="modal fade" id="myCountExportToOLMModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Export Result</h3>
                </div>
                <div class="modal-body">
                    <!-- HoaTV - Add total contact -->
                    <p style="font-size: 25px" id="total_contact">...</p>
                    <p class="text-primary" style="font-size: 25px" id="contact_success">...</p>
                    <p class="text-warning" style="font-size: 25px" id="contact_duplicate">...</p>
                    <p class="text-danger" style="font-size: 25px" id="contact_error">...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" name="page_size" value="{{$page_size}}">
    <input type="hidden" name="exported" value="{{$export_to_excel}}">
    <input type="hidden" name="export_to_olm" value="{{$export_to_olm}}">
    <input type="hidden" name="exported_url" value="{{route("contacts.countExported")}}">
    <input type="hidden" name="update_status_export" value="{{route("contacts-updateContacts")}}">
    <input type="hidden" name="export_to_olm_url" value="{{route("contacts.export-to-OLM")}}">
    <input type="hidden" name="update_all" value="0">
    <input type="hidden" name="status_update_all" value="">
    <input type="hidden" name="get_all_channel_url" value="{{route("channel-get-all")}}">
    <input type="hidden" name="count-export-to-olm" value="{{route("contacts.count-export-to-OLM")}}">
    <input type="hidden" name="total_contacts" value="">
    <input type="hidden" name="send_sms_url" value="{{route("contacts.send_sms")}}">
    <input type="hidden" name="get_balance_url" value="{{route("contacts.get_balance")}}">
    <input type="hidden" name="update_label_origin" value="{{route("label.update")}}">
@endsection

@section('script')

    <!-- PAGE RELATED PLUGIN(S) -->
    <script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
    <script src="{{ asset('js/label/label.js') }}"></script>
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
    <style>
        .loader {
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #8ac38b; /* Green */
            border-bottom: 8px solid #8ac38b; /* Green */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .select2-container
        {
            width: auto;  !important;
        }
    </style>

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
            initDataTable();

            // HoaTV multiple select
            $('input[name=channel_id]').selectize({
                delimiter: ',',
                persist: false,
                valueField: 'name',
                labelField: 'name',
                searchField: ['name'],
                options: {!! $channel !!}

            });
        });

    </script>

@stop
