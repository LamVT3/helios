<table id="table_phone_test" class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <tr>
        <th>Phone</th>
        <th>Status</th>
        <th>Created at</th>
        <th>Creator</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->phone }}</td>
            <td>
                @if($item->status == "1")
                    Pass
                @else
                    Fail
                @endif
            </td>
            <td>{{ date_format(date_create($item->created_at),"d/m/Y H:i:s") }}</td>
            <td>{{ $item->creator }}</td>
        </tr>
    @endforeach
    </tbody>
</table>