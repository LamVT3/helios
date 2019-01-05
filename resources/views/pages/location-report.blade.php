@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-toggle="modal"
                   data-target="#importContactModal"><i
                            class="fa fa-upload fa-lg"></i> Import Contact</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget', ['id' => 'location', 'icon' => 'fa-line-chart', 'title' => "Location ", 'dropdown' => 'false'])
                            <!-- widget content -->
                            <div class="widget-body no-padding flot_channel">
                                <div id="location_chart" class="chart has-legend"></div>
                            </div>
                        @endcomponent

                        @component('components.jarviswidget', ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report'])
                            <div id="wrapper_report">
                                <table id="table_location_report" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Contact ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Registered at</th>
                                        <th>IP</th>
                                        <th>Location</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>

                        @endcomponent
                        </div>

                    </article>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <input type="hidden" name="page_size" value="{{$page_size}}">

    <!-- END MAIN PANEL -->
<div class="modal fade" id="importContactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form-import-contact-l8" class="form-horizontal" action="{{ route("location-report-import") }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h3 class="modal-title"> Import Contact</h3>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div id="form-source-alert"></div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Input File</label>
                        <div class="col-md-10">
                            <input type="file" style="width: 450px; text-align: left" name="import" class="btn btn-default" id="import_file">

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            Sample Files: <a href="{{ asset('sample/import_contacts_l8.xlsx') }}" target="_blank">import_contact_l8.xlsx</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="import_contact_l8" type="button" class="btn btn-primary">
                        <i class="fa fa-upload"></i>
                        Import
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <div class="loading_modal" style="display: none">
                        <div class="col-md-12 text-center">
                            <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 5%;"/>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>

<script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
<script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>

<style>
    .flot_channel .flot-x-axis .flot-tick-label {
    white-space: nowrap;
    transform: translate(-9px, 0) rotate(-65deg);
    text-indent: -100%;
    transform-origin: top right;
    text-align: right !important;
    margin-bottom: 30px;
}
</style>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {
        init_table();

        $('#importContactModal').modal('show');

        $('button#import_contact_l8').click(function (e) {
            e.preventDefault();

            if($('#import_file').val() == ''){
                return;
            }

            $('div.loading_modal').show();

            var form = $('#form-import-contact-l8')[0];
            var data = new FormData(form);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $('#form-import-contact-l8').attr('action'),
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function(response){
                    $('#wrapper_report').html(response);
                    $('div.loading_modal').hide();
                    $('#importContactModal').modal('hide');
                }
            });
        });

    });

    function init_table(){
        var page_size       = $('input[name="page_size"]').val();

        /* BASIC ;*/
        var responsiveHelper_table_team = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_location_report').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_team) {
                    responsiveHelper_table_team = new ResponsiveDatatablesHelper($('#table_location_report'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_team.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_team.respond();
            },
            "order": [],
            "iDisplayLength": parseInt(page_size),
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
            "destroy": true,
        });
    }

    function initLocationChart() {
        var item = $("#location_chart");
        var array_value = JSON.parse($('input#location_value').val());
        var array_key   = JSON.parse($('input#location_key').val());
        var data_value  = [{data : array_value    , label : "Location", color: "#FF8C00"}];
        var data_key    = array_key;

        initChart(item, data_value, data_key);
        item.UseTooltip();
    }

    function initChart(item, value, key){

        if (item.length) {
            $.plot(item, value,
                {
                    series : {
                        lines : {
                            show : true,
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.1
                                }, {
                                    opacity : 0.15
                                }]
                            }
                        },
                        points : {
                            show : true
                        },
                        shadowSize : 0
                    },
                    xaxis : {
                        ticks: key
                    },

                    yaxes : [{
                        min : 0
                    }],
                    grid : {
                        hoverable : true,
                        clickable : true,
                        tickColor : '#efefef',
                        borderWidth : 0,
                        borderColor : '#efefef',
                    },
                    colors : '#CC0099',
                });
        }
        /* end site stats */
    }

    var previousPoint = null, previousLabel = null;
    $.fn.UseTooltip = function () {
        $(this).bind("plothover", function (event, pos, item) {
            if (item) {
                if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                    previousPoint = item.dataIndex;
                    previousLabel = item.series.label;
                    $("#tooltip").remove();

                    var x = item.datapoint[0];
                    var y = item.datapoint[1];

                    var color = item.series.color;

                    showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.xaxis.ticks[x].label + "</strong>: " +  y + "(L8)");
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    };

    function showTooltip(x, y, color, contents) {
        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y - 40,
            left: x - 40,
            border: '2px solid ' + color,
            padding: '3px',
            'font-size': '9px',
            'border-radius': '5px',
            'background-color': '#fff',
            'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            opacity: 0.9
        }).appendTo("body").fadeIn(200);
    }


</script>
@stop
