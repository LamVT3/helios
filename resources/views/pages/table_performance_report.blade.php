<table id="table_performance_report" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th width="13%">Makerter</th>
        <th width="10%">C3 Produce</th>
        <th width="10%">C3 Transfer</th>
        <th width="10%">C3 Inventory</th>
        <th>C3/L1(%)</th>
        <th>C3/L3(%)</th>
        <th>C3/L6(%)</th>
        <th>C3/L8(%)</th>
        <th>Spent(USD)</th>
        <th>C3 Cost(USD)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
    <tr>
        <td>{{$key}}</td>
        <td>{{$item['c3b_produce']}}</td>
        <td>{{$item['c3b_transfer']}}</td>
        <td>{{$item['c3b_inventory']}}</td>
        <td>{{$item['c3_l1']}}</td>
        <td>{{$item['c3_l3']}}</td>
        <td>{{$item['c3_l6']}}</td>
        <td>{{$item['c3_l8']}}</td>
        <td>{{$item['spent']}}</td>
        <td>{{$item['c3_cost']}}</td>
    </tr>
    @endforeach
    </tbody>

</table>