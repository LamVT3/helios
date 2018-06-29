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
                            class="fa fa-map-signs fa-lg"></i> Assign KPI</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">
                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report KPI'])
                            <div id="wrapper_kpi">
                                <table id="table_kpi" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th colspan="4"></th>

                                        @for ($i = 1; $i <= $days; $i++)
                                            <th colspan="2"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
                                        @endfor

                                    </tr>
                                    <tr>
                                        <td><span style="font-weight:bold">User</span></td>
                                        <td><span style="font-weight:bold">Plan</span></td>
                                        <td><span style="font-weight:bold">Actual</span></td>
                                        <td><span style="font-weight:bold">Remain</span></td>

                                        @for ($i = 1; $i <= $days; $i++)
                                            <td>KPI</td>
                                            <td>Act</td>
                                        @endfor

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $user => $item)
                                        <tr>
                                            <td>{{ $user }}</td>
                                            <td>{{ $item['total_kpi'] }}</td>
                                            <td>{{ $item['total_c3b'] }}</td>
                                            <td>{{ $item['total_c3b'] - $item['total_kpi'] }}</td>
                                            @for ($i = 1; $i <= $days; $i++)
                                                <td>{{ $item['kpi'][$i] ? $item['kpi'][$i] : 0 }}</td>
                                                <td>{{ @$item['c3b'][$i] ? @$item['c3b'][$i] : 0 }}</td>
                                            @endfor

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

                @include('components.form-assign-kpi', ['type' => null])
        </div>

        <!-- END MAIN CONTENT -->

    </div>
    <!-- END MAIN PANEL -->
    <input type="hidden" id="get_kpi_url" value="{{route('get-kpi')}}">


@endsection


@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>--}}


<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        $("#table_kpi").tableHeadFixer({"left" : 4, 'z-index': 0});

        var month = moment().month() + 1;
        if(month < 10){
            month = '0' + month;
        }
        $('#month').val(month);
        initFormKPI(month);

        $('#assign-kpi').click(function(e){
            e.preventDefault();
            var url = $('#form-assign-kpi').attr( 'url' );

            var year = moment().year();
            var month = $('select#month').val();

            var cnt = 1;
            var kpi = {};

            $('input#day').each(function() {
                var value = $(this).val();
                if (!value){
                    value = 0;
                }
                kpi[cnt] = value;
                cnt++;
            });

            var data ={};
            data.kpi        = kpi;
            data.month      = month;
            data.year       = year;
            data.username   = $('input#username').val();

            $.get(url, data, function (data) {

            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        });

        $('select#month').change(function(e){
            e.preventDefault();
            var month = $(this).val();

            initFormKPI(month);
        });

        /* END BASIC */
    })

    function initFormKPI(month){

        initLstDays(month);
    }

    function initLstDays(month) {
        var year = moment().year();
        var days = new Date(year, month, 0).getDate();
        var url  = $('input#get_kpi_url').val();

        var data ={};
        data.month      = month;
        data.year       = year;
        data.username   = $('input#username').val();

        $.get(url, data, function (data) {
            $("div.lst_days").html('');
            var i;
            for (i = 1; i <= days; i++) {
                var value = data[i] ? data[i] : 0;
                var item =
                    '<section class="col col-2">' +
                    i + '/' + month + '/' + year +
                    '    <input class="form-control" id="day" type="number" value="'+ value +'"' +
                    '           placeholder="" max="" min="1" data-toggle="tooltip" title="Enter KPIs...">' +
                    '</section>';

                $("div.lst_days").append(item);
            }
        }).fail(
            function (err) {
                alert('Cannot connect to server. Please try again later.');
            });
    }

</script>
@stop
