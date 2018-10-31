<table id="table_phone_check" class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Date Submit</th>
        <th>Date Check</th>
        <th>Result</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $item)
        <tr id="">
            <td>{{ $item->name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->date}}</td>
            <td>{{ $item->created_date->toDateTimeString() }}</td>
            <td>
                @if($item->result == "1")
                    OK
                    @else
                    Fail
                @endif
            </td>
        </tr>

    @endforeach
    </tbody>
</table>