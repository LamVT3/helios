<table id="table_kpi" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th colspan="6" class="border-bold-right"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="2" style="text-align: center" class="border-bold-right"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
        @endfor

    </tr>
    <tr>
        <th class="no-border-right" style="border-left: none"></th>
        <th class="no-border-right">Marketer</th>
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
        $noMarketer = sizeof($data_maketer);
    ?>
    @foreach($data_maketer as $user => $item)
        <?php
            $total_kpi += @$item['total_kpi'];
            $total_actual += @$item['total_actual'];
        ?>
    @endforeach
    <tr style="font-weight: bold; color: #3276b1; font-size: medium;">
        <td class="no-border-right" style="border-left: none"></td>
        <td class="no-border-right"><span>Total</span></td>
        <td class="border-bold-right">
            {{--<a class=' btn-xs btn-default edit_kpi' data-user-id=""--}}
               {{--href="" data-toggle="" data-target="" onclick=""--}}
               {{--data-original-title='Edit Row'><i class='fa fa-pencil'></i></a>--}}
        </td>
        <?php
            $total_kpi = $kpi_selection == "c3b" ? $total_kpi : ($noMarketer != 0 ? round($total_kpi/$noMarketer,2) : 0);
            $total_actual = $kpi_selection == "c3b" ? $total_actual : ($noMarketer != 0 ? round($total_actual/$noMarketer,2) : 0);
            $total_gap = $kpi_selection == "c3b_cost" ? ($total_kpi - $total_actual) : ($total_actual - $total_kpi) ;
        ?>
        <td class="border-bold-right">{{ $total_kpi }}</td>
        <td class="border-bold-right">{{ $total_actual }}</td>
        <td class="border-bold-right">{{ $total_gap }}</td>

        @for ($i = 1; $i <= $days; $i++)
            <?php $kpi = $actual = 0; ?>
            @foreach($data_maketer as $user => $item)
                <?php
                    $kpi += @$item['kpi'][$i];
                    $actual += @$item['actual'][$i];
                ?>
            @endforeach

            <td>{{ $kpi_selection == "c3b" ? $kpi : ($noMarketer != 0 ? round($kpi/$noMarketer,2) : 0) }}</td>
            <td class="border-bold-right act">{{ $kpi_selection == "c3b" ? $actual : ($noMarketer != 0 ? round($actual/$noMarketer,2) : 0) }}</td>
        @endfor
    </tr>
    @foreach($data_maketer as $user => $item)
        <tr data-tt-id="{{@$item['user_id']}}">
            <?php $gap = $kpi_selection == "c3b_cost" ? @$item['total_kpi'] - @$item['total_actual'] : @$item['total_actual'] - @$item['total_kpi'] ?>
            <td class="no-border-right" style="border-left: none">
                <a href="javascript:void(0)" title="Show detail" class="channel__show"><i class="fa fa-plus-circle"></i></a>
                <a href="javascript:void(0)" title="Hide detail" class="channel__hide channel_hidden"><i class="fa fa-minus-circle"></i></a>
            </td>
            @if($gap < 0)
                <td class="no-border-right gap_text" style="text-align: left">
                    <span class="bolder_text">{{ @$user }}</span>
                </td>
            @else
                <td class="no-border-right" style="text-align: left">
                    <span class="bolder_text">{{ @$user }}</span>
                </td>
            @endif

            <td class="border-bold-right">
                <a class=' btn-xs btn-default edit_kpi' data-user-id="{{@$item['user_id']}}"
                   href="" data-toggle="modal" data-target="#addModal" onclick="set_user_id(this)"
                   data-original-title='Edit Row'><i
                            class='fa fa-pencil'></i></a>
            </td>

            @if($gap < 0)
                <td class="border-bold-right gap_text bolder_text">{{ @$item['total_kpi'] }}</td>
                <td class="border-bold-right gap_text bolder_text">{{ @$item['total_actual'] }}</td>
                <td class="border-bold-right gap_text bolder_text">{{ $gap }}</td>
            @else
                <td class="border-bold-right bolder_text">{{ @$item['total_kpi'] }}</td>
                <td class="border-bold-right bolder_text">{{ @$item['total_actual'] }}</td>
                <td class="border-bold-right bolder_text">{{ $gap }}</td>
            @endif

            @for ($i = 1; $i <= $days; $i++)
                <?php $gap_day = $kpi_selection == "c3b_cost" ? @$item['kpi'][$i] - @$item['actual'][$i] : @$item['actual'][$i] - @$item['kpi'][$i] ?>
                @if(@$gap_day < 0)
                    <td class="gap_text">{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right act gap_text ">
                        {{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}
                        <span>({{@$gap_day}})</span>
                    </td>
                @else
                    <td>{{ @$item['kpi'][$i] ? @$item['kpi'][$i] : 0 }}</td>
                    <td class="border-bold-right ">
                        {{ @$item['actual'][$i] ? @$item['actual'][$i] : 0 }}
                    </td>
                @endif
            @endfor
        </tr>
        @if(isset($item['channels']))
            @foreach($item['channels'] as $key => $value)
                <?php $gap = $kpi_selection == "c3b_cost" ? $value['total_kpi'] - $value['total_actual'] : $value['total_actual'] - $value['total_kpi'] ?>
                <tr data-tt-parent-id="{{@$item['user_id']}}" class="channel_hidden">
                    <td class="no-border-right" style="border-left: none"></td>
                    @if($gap < 0)
                        <td class="no-border-right gap_text" style="text-align: center"><span>{{$key}}</span></td>
                        <td class="border-bold-right"></td>
                        <td class="border-bold-right gap_text">{{ $value['total_kpi'] }}</td>
                        <td class="border-bold-right gap_text">{{ $value['total_actual'] }}</td>
                        <td class="border-bold-right gap_text">{{$gap}}</td>
                    @else
                        <td class="no-border-right" style="text-align: center"><span>{{$key}}</span></td>
                        <td class="border-bold-right"></td>
                        <td class="border-bold-right">{{ $value['total_kpi'] }}</td>
                        <td class="border-bold-right">{{ $value['total_actual'] }}</td>
                        <td class="border-bold-right">{{$gap}}</td>
                    @endif

                    @for ($i = 1; $i <= $days; $i++)
                        <?php
                            $value['kpi'][$i] = isset($value['kpi'][$i]) ? $value['kpi'][$i] : 0;
                            $value['actual'][$i] = isset($value['actual'][$i]) ? $value['actual'][$i] : 0;
                            $gap_day = $kpi_selection == "c3b_cost" ? $value['kpi'][$i] - $value['actual'][$i] : $value['actual'][$i] - $value['kpi'][$i];
                        ?>
                        @if($gap_day < 0)
                            <td class="gap_text">{{ $value['kpi'][$i] }}</td>
                            <td class="border-bold-right gap_text">{{ $value['actual'][$i] }}<span>({{$gap_day}})</span></td>
                        @else
                            <td>{{ $value['kpi'][$i] }}</td>
                            <td class="border-bold-right">{{ $value['actual'][$i] }}</td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>
