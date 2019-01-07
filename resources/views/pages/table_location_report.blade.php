
<table id="table_location_report" class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <tr>
        <th>Contact ID</th>
        <th>Location</th>
        <th>IP</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Registered at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contacts as $contact)
        <tr>
            <td>{{@$contact['contact_id']}}</td>
            <td>{{@$contact['location']}}</td>
            <td>{{@$contact['ip']}}</td>
            <td>{{@$contact['name']}}</td>
            <td>{{@$contact['phone']}}</td>
            <td>{{@$contact['email']}}</td>
            <td>{{ Date('d-m-Y H:i:s', @$contact['submit_time'] / 1000) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<input id="location_key" type="hidden" value="{{@$location_key}}">
<input id="location_value" type="hidden" value="{{@$location_value}}">

<script type="text/javascript">
    $(document).ready(function () {
        init_table();
        initLocationChart();
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


</script>