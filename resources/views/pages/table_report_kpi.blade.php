<table id="table_kpi" class="table table-hover ">
    <thead>
    <tr>
        <th colspan="5"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="2" style="text-align: center"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
        @endfor

    </tr>
    <tr>
        <th>User</th>
        <th></th>
        <th>Plan</th>
        <th>Actual</th>
        <th>Remain</th>

        @for ($i = 1; $i <= $days; $i++)
            <th>KPI</th>
            <th>Act</th>
        @endfor

    </tr>
    </thead>
    <tbody>
    @foreach($data as $user => $item)
        <tr>
            <td><span style="font-weight: bold">{{ $user }}</span></td>
            <td>
                <a class=' btn-xs btn-default edit_kpi' data-user-id="{{@$item['user_id']}}"
                   href="" data-toggle="modal" data-target="#addModal" onclick="set_user_id(this)"
                   data-original-title='Edit Row'><i
                            class='fa fa-pencil'></i></a>
            </td>
            <td>{{ @$item['total_kpi'] }}</td>
            <td>{{ @$item['total_c3b'] }}</td>
            <td>{{ @$item['total_c3b'] - @$item['total_kpi'] }}</td>
            @for ($i = 1; $i <= $days; $i++)
                <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                <td>{{ @$item['c3b'][$i] ? @$item['c3b'][$i] : 0 }}</td>
            @endfor

        </tr>
    @endforeach
    </tbody>
</table>