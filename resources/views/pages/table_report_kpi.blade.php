<table id="table_kpi" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th colspan="5" class="border-bold-right"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="2" style="text-align: center" class="border-bold-right"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
        @endfor

    </tr>
    <tr>
        <th class="no-border-right">Maketer</th>
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
    <?php
        $total_kpi = $total_actual = 0;
    ?>
    @foreach($data_maketer as $user => $item)
        <?php
            $total_kpi += @$item['total_kpi'];
            $total_actual += @$item['total_actual'];
        ?>
    @endforeach
    <tr style="font-weight: bold">
        <td class="no-border-right"><span>Total</span></td>
        <td class="border-bold-right">
            {{--<a class=' btn-xs btn-default edit_kpi' data-user-id=""--}}
               {{--href="" data-toggle="" data-target="" onclick=""--}}
               {{--data-original-title='Edit Row'><i class='fa fa-pencil'></i></a>--}}
        </td>
        <td class="border-bold-right">{{ $total_kpi }}</td>
        <td class="border-bold-right">{{ $total_actual }}</td>
        <td class="border-bold-right">{{ $total_actual - $total_kpi }}</td>

        @for ($i = 1; $i <= $days; $i++)
            <?php $kpi = $actual = 0; ?>
            @foreach($data_maketer as $user => $item)
                <?php
                    $kpi += @$item['kpi'][$i];
                    $actual += @$item['actual'][$i];
                ?>
            @endforeach
            <td>{{ $kpi }}</td>
            <td class="border-bold-right act">{{ $actual }}</td>
        @endfor
    </tr>
    @foreach($data_maketer as $user => $item)
        <tr>
            <?php $gap = @$item['total_actual'] - @$item['total_kpi']?>

            @if($gap < 0)
                <td class="no-border-right gap_text"><span style="font-weight: bold">{{ @$user }}</span></td>
            @else
                <td class="no-border-right"><span style="font-weight: bold">{{ @$user }}</span></td>
            @endif

            <td class="border-bold-right">
                <a class=' btn-xs btn-default edit_kpi' data-user-id="{{@$item['user_id']}}"
                   href="" data-toggle="modal" data-target="#addModal" onclick="set_user_id(this)"
                   data-original-title='Edit Row'><i
                            class='fa fa-pencil'></i></a>
            </td>
            <td class="border-bold-right">{{ @$item['total_kpi'] }}</td>
            <td class="border-bold-right">{{ @$item['total_actual'] }}</td>

            @if($gap < 0)
                <td class="border-bold-right gap_text">{{ $gap }}</td>
            @else
                <td class="border-bold-right">{{ $gap }}</td>
            @endif

            @for ($i = 1; $i <= $days; $i++)
                @if(@$item['actual'][$i] - @$item['kpi'][$i] < 0)
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right act">
                        {{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}
                        <span class="gap_text">({{@$item['actual'][$i] - @$item['kpi'][$i]}})</span>
                    </td>
                @else
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right">{{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}</td>
                @endif
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>