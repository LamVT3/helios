<table id="table_kpi_by_team" class="table table-hover table-bordered table-responsive">
    <thead>
    <tr>
        <th colspan="5" class="border-bold-right"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="2" style="text-align: center" class="border-bold-right"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
        @endfor

    </tr>
    <tr>
        <th class="no-border-right">Team</th>
        <th class="border-bold-right" style="border-left: none"></th>
        <th class="border-bold-right">Plan</th>
        <th class="border-bold-right">Actual</th>
        <th class="border-bold-right">GAP</th>

        @for ($i = 1; $i <= $days; $i++)
            <th>KPI</th>
            <th class="border-bold-right">Act</th>
        @endfor

    </tr>
    </thead>
    <tbody>
    @foreach($data_team as $team => $item)
        <tr>
            <?php $gap =  @$item['total_c3b'] - @$item['total_kpi']?>

            @if($gap < 0)
                <td class="no-border-right gap_text"><span style="font-weight: bold">{{ $team }}</span></td>
            @else
                <td class="no-border-right"><span style="font-weight: bold">{{ $team }}</span></td>
            @endif

            <td class="border-bold-right">

            </td>
            <td class="border-bold-right">{{ @$item['total_kpi'] }}</td>
            <td class="border-bold-right">{{ @$item['total_c3b'] }}</td>

            @if($gap < 0)
                <td class="border-bold-right gap_text">{{ $gap }}</td>
            @else
                <td class="border-bold-right">{{ $gap }}</td>
            @endif

            @for ($i = 1; $i <= $days; $i++)
                @if(@$item['c3b'][$i] - @$item['kpi'][$i] < 0)
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right act">
                        {{ @$item['c3b'][$i] ? @$item['c3b'][$i] : 0 }}
                        <span class="gap_text">({{@$item['c3b'][$i] - @$item['kpi'][$i]}})</span>
                    </td>
                @else
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right">{{ @$item['c3b'][$i] ? @$item['c3b'][$i] : 0 }}</td>
                @endif
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>