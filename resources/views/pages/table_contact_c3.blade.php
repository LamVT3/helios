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
            <td><a href="{{ route("contacts-details", $item->id) }}">{{ $item->name }}</a></td>
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
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        /* BASIC ;*/
        var responsiveHelper_table_campaign = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_campaigns').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_campaign) {
                    responsiveHelper_table_campaign = new ResponsiveDatatablesHelper($('#table_campaigns'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_campaign.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_campaign.respond();
            },
            "order": [[0, "desc"]]
        });


//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */
    });

    $(document).ready(function () {
        $('#search-form-c3').submit(function (e) {
            e.preventDefault();
            var source_id = $('select[name="source_id"]').val();
            var team_id = $('select[name="team_id"]').val();
            var marketer_id = $('select[name="marketer_id"]').val();
            var campaign_id = $('select[name="campaign_id"]').val();
            var url = "{!! route('contacts.filter') !!}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    source_id: source_id,
                    team_id: team_id,
                    marketer_id: marketer_id,
                    campaign_id: campaign_id
                }
            }).done(function (response) {
                $('.wrapper').html(response);
            });
        });
    })

</script>