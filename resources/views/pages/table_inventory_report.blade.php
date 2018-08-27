<table id="table_inventory_report" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th colspan="2" class="border-bold-right"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="3" style="text-align: center" class="border-bold-right"> {{$i < 10 ? '0'.$i.'/'.$month.'/'.$year : $i.'/'.$month.'/'.$year}} </th>
        @endfor

    </tr>
    <tr>
        <th class="border-bold-bot-right" style="min-width: 250px; text-align: center">Channel</th>
        <th class="border-bold-bot-right" style="min-width: 100px; text-align: center">Total Inventory</th>

        @for ($i = 1; $i <= $days; $i++)
            <th class="border-bold-bot" style="font-size: 10px">C3 Produce</th>
            <th class="border-bold-bot" style="font-size: 10px">C3 Transfer</th>
            <th style="font-size: 10px" class="border-bold-bot-right">Inventory</th>
        @endfor

    </tr>
    </thead>
    <tbody>
    @foreach($data['lable'] as $source => $channel)
        <tr>
            <td class="border-bold-right" style="min-width: 200px">{{$source}}</td>

            <td class="border-bold-right" style="font-weight: bold">{{@$data['total_source'][$source] ? $data['total_source'][$source] : 0}}</td>
            @for ($i = 1; $i <= $days; $i++)
            <td style="font-size: 15px">{{@$data['data'][$source][$i]['produce']?:0}}</td>
            <td style="font-size: 15px">{{@$data['data'][$source][$i]['transfer']?:0}}</td>
            <td class="border-bold-right" style="font-size: 15px">{{@$data['data'][$source][$i]['inventory']?:0}}</td>
            @endfor
        </tr>

        @foreach($channel as $item)
        <tr>
            <td class="border-bold-right" style="min-width: 200px; text-align: right">{{$item}}</td>

            <td class="border-bold-right" style="font-weight: bold">{{@$data['total_channel'][$item] ? $data['total_channel'][$item] : 0}}</td>
            @for ($i = 1; $i <= $days; $i++)
            <td style="font-size: 15px">{{$data['data'][$item][$i]['produce']?:0}}</td>
            <td style="font-size: 15px">{{$data['data'][$item][$i]['transfer']?:0}}</td>
            <td class="border-bold-right" style="font-size: 15px">{{$data['data'][$item][$i]['inventory']?:0}}</td>
            @endfor
        </tr>
        @endforeach





    @endforeach
    </tbody>
    <tfoot>
    <tr style="background-color: #F5F5F5">
        <th colspan="2" class="border-bold-top-bot-right" style="text-align: right;">Total</th>
        @for ($i = 1; $i <= $days; $i++)
            <td class="border-bold-top-bot" style="font-size: 15px; font-weight: bold">{{$data['total'][$i]['produce']}}</td>
            <td class="border-bold-top-bot" style="font-size: 15px; font-weight: bold">{{$data['total'][$i]['transfer']}}</td>
            <td class="border-bold-top-bot-right" style="font-size: 15px; font-weight: bold">{{$data['total'][$i]['inventory']}}</td>
        @endfor
    </tr>
    <tr style="background-color: #F5F5F5">
        <th colspan="2" class="border-bold-bot-right" style="text-align: right; background-color: rgb(238, 238, 238)">Grand Total</th>
        <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{$data['grand_total']['produce']}}</td>
        <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{$data['grand_total']['transfer']}}</td>
        <td class="border-bold-bot-right" style="font-size: 15px; font-weight: bold">{{$data['grand_total']['inventory']}}</td>
        <td colspan="90" class="" style="font-size: 15px; font-weight: bold"></td>
    </tr>
    </tfoot>

</table>