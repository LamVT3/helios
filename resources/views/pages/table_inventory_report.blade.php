<table id="table_inventory_report" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th class="border-bold-right"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="3" style="text-align: center" class="border-bold-right"> {{$i < 10 ? '0'.$i.'/'.$month.'/'.$year : $i.'/'.$month.'/'.$year}} </th>
        @endfor

    </tr>
    <tr>
        <th class="border-bold-right" style="min-width: 200px">Channel</th>

        @for ($i = 1; $i <= $days; $i++)
            <th style="min-width: 80px; font-size: 10px">C3 Produce</th>
            <th style="min-width: 80px; font-size: 10px">C3 Transfer</th>
            <th style="font-size: 10px" class="border-bold-right">Inventory</th>
        @endfor

    </tr>

    @foreach()
    <tr>
        <td class="border-bold-right" style="min-width: 200px">00000</td>

        @for ($i = 1; $i <= $days; $i++)
            <td style="min-width: 80px;">111</td>
            <td style="min-width: 80px;">2222</td>
            <td class="border-bold-right">333</td>
        @endfor

    </tr>

    </thead>
    <tbody>
    </tbody>
</table>