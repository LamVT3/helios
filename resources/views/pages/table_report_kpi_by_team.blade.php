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
        <th class="border-bold-right">KPI</th>
        <th class="border-bold-right">Actual</th>
        <th class="border-bold-right">GAP</th>

        @for ($i = 1; $i <= $days; $i++)
            <th>KPI</th>
            <th class="border-bold-right">Act</th>
        @endfor

    </tr>
    </thead>
    <tbody>

    <?php
        $total_kpi = $total_actual = 0;
        $noTeam = sizeof($data_team);
    ?>
    @foreach($data_team as $user => $item)
        <?php
            $total_kpi += @$item['total_kpi'];
            $total_actual += @$item['total_actual'];
        ?>
    @endforeach
    <tr style="font-weight: bold; color: #3276b1; font-size: medium;">
        <td class="no-border-right"><span>Total</span></td>
        <td class="border-bold-right">
            {{--<a class=' btn-xs btn-default edit_kpi' data-user-id=""--}}
            {{--href="" data-toggle="" data-target="" onclick=""--}}
            {{--data-original-title='Edit Row'><i class='fa fa-pencil'></i></a>--}}
        </td>
        <?php
            $total_kpi = $kpi_selection == "c3b" ? $total_kpi : ($noTeam != 0 ? round($total_kpi/$noTeam,2) : 0);
            $total_actual = $kpi_selection == "c3b" ? $total_actual : ($noTeam != 0 ? round($total_actual/$noTeam,2) : 0);
            $total_gap = $kpi_selection == "c3b_cost" ? ($total_kpi - $total_actual) : ($total_actual - $total_kpi) ;
        ?>
        <td class="border-bold-right total_text">{{ $total_kpi }}</td>
        <td class="border-bold-right total_text">{{ $total_actual }}</td>
        <td class="border-bold-right total_text">{{ $total_gap }}</td>

        @for ($i = 1; $i <= $days; $i++)
            <?php $kpi = $actual = 0; ?>
            @foreach($data_team as $user => $item)
                <?php
                    $kpi += @$item['kpi'][$i];
                    $actual += @$item['actual'][$i];
                ?>
            @endforeach

            <td>{{ $kpi_selection == "c3b" ? $kpi : ($noTeam != 0 ? round($kpi/$noTeam,2) : 0) }}</td>
            <td class="border-bold-right act">{{ $kpi_selection == "c3b" ? $actual : ($noTeam != 0 ? round($actual/$noTeam,2) : 0) }}</td>
        @endfor
    </tr>
    @foreach($data_team as $team => $item)
        <tr>
            <?php $gap = $kpi_selection == "c3b_cost" ? @$item['total_kpi'] - @$item['total_actual'] : @$item['total_actual'] - @$item['total_kpi'] ?>

            <td class="no-border-right"><span style="font-weight: bold">{{ $team }}</span></td>
            <td class="border-bold-right"></td>

            <td class="border-bold-right bolder_text total_text">{{ @$item['total_kpi'] }}</td>
            @if($gap < 0)
                <td class="border-bold-right gap_text bolder_text total_text">{{ @$item['total_actual'] }}</td>
                <td class="border-bold-right gap_text bolder_text total_text">{{ $gap }}</td>
            @else
                <td class="border-bold-right bolder_text total_text">{{ @$item['total_actual'] }}</td>
                <td class="border-bold-right bolder_text total_text">{{ $gap }}</td>
            @endif

            @for ($i = 1; $i <= $days; $i++)
                <?php $gap_day = $kpi_selection == "c3b_cost" ? @$item['kpi'][$i] - @$item['actual'][$i] : @$item['actual'][$i] - @$item['kpi'][$i] ?>
                @if(@$gap_day < 0)
                    <td class="gap_text">{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right act gap_text">{{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}</td>
                @else
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right">
                        {{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}
                    </td>
                @endif
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>