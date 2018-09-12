<table id="table_performance_report" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th width="13%">Makerter</th>
        <th width="10%">C3 Produce</th>
        <th width="10%">C3 Transfer</th>
        <th width="10%">C3 Inventory</th>
        <th>C3/L1</th>
        <th>C3/L3</th>
        <th>C3/L6</th>
        <th>C3/L8</th>
        <th>Spent</th>
        <th>C3 Cost</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
    <tr>
        <td>{{$key}}</td>
        <td>{{$item['c3b_produce']}}</td>
        <td>{{$item['c3b_transfer']}}</td>
        <td>{{$item['c3b_inventory']}}</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    </tr>
    @endforeach
    </tbody>

</table>