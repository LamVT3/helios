<table id="table_performance_report" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th class="border-bold-bot-right" width="15%">Makerter</th>
        <th class="border-bold-bot" width="10%">C3 Produce</th>
        <th class="border-bold-bot" width="10%">C3 Transfer</th>
        <th class="border-bold-bot" width="10%">C3 Inventory</th>
        <th class="border-bold-bot">C3/L1(%)</th>
        <th class="border-bold-bot">C3/L3(%)</th>
        <th class="border-bold-bot">C3/L6(%)</th>
        <th class="border-bold-bot">C3/L8(%)</th>
        <th class="border-bold-bot">Spent(USD)</th>
        <th class="border-bold-bot">C3 Cost(USD)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
    <tr>
        <td class="border-bold-right" style="font-weight: bold">{{$key}}</td>
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