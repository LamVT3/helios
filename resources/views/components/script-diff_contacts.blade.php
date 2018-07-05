<script>
    $(document).ready(function () {
        inittable($('#table_mol_helios'));
        inittable($('#table_helios_mol'));
    })

    function inittable(table){
        var page_size   = $('input[name="page_size"]').val();
        /* BASIC ;*/
        var responsiveHelper_table_channel = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        table.dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_channel) {
                    responsiveHelper_table_channel = new ResponsiveDatatablesHelper(table, breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_channel.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_channel.respond();
            },
            "order": [[4, "desc"]],
            "iDisplayLength": parseInt(page_size),
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
            "destroy": true,
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false,
                }
            ]
        });
    }

    $(function(){

        var start = moment();
        var end = moment();

        function reportrange_span(start, end) {
            var init_date = new Date('6/17/2018');

            if(start < init_date){
                $('#reportrange span').html('17/6/2018' + '-' + end.format('D/M/Y'));
            }else{
                $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
            }
        }

        reportrange_span(start, end);

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            opens: 'right',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
                "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, reportrange_span);

        $('#reportrange').on('hide.daterangepicker', function(ev, picker) {

            var url         = $('input[name=filter_url]').val();
            $('.loading').show();
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    registered_date : $('.registered_date').text()
                }
            }).done(function (response) {
                $('.wrapper_table').html(response);
                inittable($('#table_mol_helios'));
                inittable($('#table_helios_mol'));
            });
            setTimeout(function(){
                $('.loading').hide();
            }, 3000);
        });

    });

</script>