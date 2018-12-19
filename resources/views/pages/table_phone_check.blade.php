<table id="table_phone_check" class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <tr>
        {{--<th>Name</th>--}}
        <th>Phone</th>
        <th>Date Submit</th>
        <th>Date Check</th>
        <th>Send Status</th>
        <th>Result</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $item)
        <tr>
{{--            <td>{{ $item->name }}</td>--}}
            <td>{{ $item->phone }}</td>
            <td>{{date_format(date_create( $item->date),"d/m/Y")}}</td>
            <td>{{date_format(date_create($item->created_date),"d/m/Y") }}</td>
            <td>
                @if($item->send_result == "1")
                    OK
                    @else
                    Fail
                @endif
            </td>
            <td>{{$item->status}}</td>
        </tr>

    @endforeach
    </tbody>
</table>