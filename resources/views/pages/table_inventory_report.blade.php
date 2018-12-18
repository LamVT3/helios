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
    <tr style="background-color: #F5F5F5">
        <th colspan="2" class="border-bold-bot-right" style="text-align: right;">Total</th>
        @for ($i = 1; $i <= $days; $i++)
            <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{@$data['total']['produce'][$i]}}</td>
            <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{@$data['total']['transfer'][$i]}}</td>
            <td class="border-bold-bot-right" style="font-size: 15px; font-weight: bold">{{@$data['total']['inventory'][$i]}}</td>
        @endfor
    </tr>
    </thead>
    <tbody>

    @foreach($data['data'] as $source => $channel)

        <tr style="background-color: #F5F5F5; font-weight: bold">
            <td class="border-bold-right" style="min-width: 200px">{{$source}}</td>

            <td class="border-bold-right" style="">{{@$data['total_source'][$source] ? $data['total_source'][$source] : 0}}</td>
            @for ($i = 1; $i <= $days; $i++)
            <td style="font-size: 15px">{{@$data[$source]['produce'][$i]?:0}}</td>
            <td style="font-size: 15px">{{@$data[$source]['transfer'][$i]?:0}}</td>
            <td class="border-bold-right" style="font-size: 15px">{{@$data[$source]['inventory'][$i]?:0}}</td>
            @endfor
        </tr>

        @foreach($channel as $label => $item)
            @if($label != '')
                <tr>
                    <td class="border-bold-right" style="min-width: 200px; text-align: right">{{$label}}</td>

                    <td class="border-bold-right" style="font-weight: bold">{{@$data['total_channel'][$source][$label] ? $data['total_channel'][$source][$label] : 0}}</td>
                    @for ($i = 1; $i <= $days; $i++)
                    <td style="font-size: 15px">{{@$item['produce'][$i]?:0}}</td>
                    <td style="font-size: 15px">{{@$item['transfer'][$i]?:0}}</td>
                    <td class="border-bold-right" style="font-size: 15px">{{@$item['inventory'][$i]?:0}}</td>
                    @endfor
                </tr>
            @endif
        @endforeach

    @endforeach
    </tbody>
    <tfoot>
    <tr style="background-color: #F5F5F5">
        <th colspan="2" class="border-bold-top-bot-right" style="text-align: right;">Total</th>
        @for ($i = 1; $i <= $days; $i++)
            <td class="border-bold-top-bot" style="font-size: 15px; font-weight: bold">{{@$data['total']['produce'][$i]}}</td>
            <td class="border-bold-top-bot" style="font-size: 15px; font-weight: bold">{{@$data['total']['transfer'][$i]}}</td>
            <td class="border-bold-top-bot-right" style="font-size: 15px; font-weight: bold">{{@$data['total']['inventory'][$i]}}</td>
        @endfor
    </tr>
    <tr style="background-color: #F5F5F5">
        <th colspan="2" class="border-bold-bot-right" style="text-align: right; background-color: rgb(238, 238, 238)">Grand Total</th>
        <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{@$data['grand_total']['produce']}}</td>
        <td class="border-bold-bot" style="font-size: 15px; font-weight: bold">{{@$data['grand_total']['transfer']}}</td>
        <td class="border-bold-bot-right" style="font-size: 15px; font-weight: bold">{{@$data['grand_total']['inventory']}}</td>
        <td colspan="90" class="" style="font-size: 15px; font-weight: bold"></td>
    </tr>
    </tfoot>

</table>