@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">
                <div class="row">
                    <article class="col-sm-12 col-md-12">
                        @component('components.jarviswidget',
                        ['id' => 1, 'icon' => 'fa-table', 'title' => 'Inventory Report'])
                            <div class="widget-body">
                                <form id="form-inventory-report" class="smart-form" action="#"
                                      url="#">
                                    <div class="row padding" id="inventory-filter">
                                        <section class="col-2">
                                            <label class="label">Month</label>
                                            <select name="month" class="select2" style="width: 280px" id="month"
                                                    tabindex="1" autofocus
                                                    data-url="">
                                                <option value="">All</option>
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <i></i>
                                        </section>
                                    </div>
                                    <div class="row" id="inventory-filter">
                                        <section class="col-12">
                                            <label class="label">Channel</label>
                                            <input type="text" value="" name="channel" placeholder="Select channel...">
                                            <i></i>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary btn-sm" type="button" id="filter-inventory"
                                                    style="margin-right: 15px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="loading" style="display: none">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                             style="width: 2%;"/>
                                    </div>
                                </div>
                                <hr>

                                <div id="wrapper_inventory">
                                    @include('pages.table_inventory_report')
                                </div>


                            </div>
                        @endcomponent
                    </article>

                </div>
                <!-- end row -->
            </section>
            <!-- end widget grid -->
        </div>
        <!-- END MAIN CONTENT -->
    </div>
    <!-- END MAIN PANEL -->
    <input type="hidden" name="filter-inventory-report" value="{{route('filter-inventory-report')}}">

@endsection

@section('script')
    <!-- PAGE RELATED PLUGIN(S) -->
    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
    <script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
    <script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#table_inventory_report").tableHeadFixer({"left" : 2, 'foot': true, 'z-index': 1});
            var d = new Date();
            var current_month = d.getMonth() + 1;
            if(current_month < 10){
                current_month = '0' + current_month;
            }
            $('select[name=month]').val(current_month).trigger('change');;

            $('input[name=channel]').selectize({
                delimiter: ',',
                persist: false,
                valueField: '_id',
                labelField: 'name',
                searchField: ['name'],
                options: {!! $channel !!}
            });

            $('button#filter-inventory').click(function() {
                var url = $('input[name=filter-inventory-report]').val();
                var month = $('select[name=month]').val();
                var channel = $('input[name=channel]').val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        month   : month,
                        channel : channel,
                    }
                }).done(function (response) {
                    $('#wrapper_inventory').html(response);
                    $("#table_inventory_report").tableHeadFixer({"left" : 2, 'foot': true, 'z-index': 1});
                });
            });

        })

    </script>
@endsection