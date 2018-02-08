<table id="table_contacts" class="table table-striped table-bordered table-hover"
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
            <td><a href="javascript:void(0)" class="name" data-id="{{ $item->id }}">{{ $item->name }}</a></td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ Date('d-m-Y H:i:s', $item->submit_time/1000) }}</td>
            <td>{{ $item->current_level }}</td>
            <td>{{ $item->marketer_name }}</td>
            <td>{{ $item->campaign_name }}</td>
            <td>{{ $item->subcampaign_name }}</td>
            <td>{{ $item->ad_name }}</td>
            <td>{{ $item->landing_page }}</td>
            <td>
                {{--@permission('edit-review')--}}
                <a href="javascript:void(0)" class="name btn btn-default btn-xs" data-id="{{ $item->id }}"><i
                            class='fa fa-eye'></i></a>
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