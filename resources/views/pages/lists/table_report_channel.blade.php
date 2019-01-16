<div class="col-sm-12">
    <article class="col-sm-12 col-md-12" style="overflow: auto; margin-bottom: 30px">
        <table class="table table-bordered table-hover"
               width="100%" style="margin-bottom: 0px">
            <thead>
            <tr>
                <th width="10px"></th>
                <th>Channel</th>
                <th>C3</th>
                <th>C3B</th>
                <th>C3BG</th>
                <th>C3BG/C3B (%)</th>
                <th>L1</th>
                <th>L3</th>
                <th>L6</th>
                <th>L8</th>
                <th>L1/C3BG (%)</th>
                <th>L3/C3BG (%)</th>
                <th>L6/L1 (%)</th>
                <th>L8/L1 (%)</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($array_channel as $i)
                    <tr class="tr_channel">
                        <td><a href="javascript:void(0)" title="Show detail" class="ads__show" data-channel_name='{{$i}}' data-api_channel='{{route('channel-ads-detail')}}'><i class="fa fa-plus-circle"></i></a></td>
                        <td>{{$i}}</td>
                        <td style="color:{{($table['c3'][$i]) >= ($table['c3_week'][$i]) ? 'green' : 'red'}}">{{$table['c3'][$i]}}</td>
                        <td style="color:{{$table['c3b'][$i] >= $table['c3b_week'][$i] ? 'green' : 'red'}}">{{$table['c3b'][$i]}}</td>
                        <td style="color:{{$table['c3bg'][$i] >= $table['c3bg_week'][$i] ? 'green' : 'red'}}">{{$table['c3bg'][$i]}}</td>
                        <td>{{($table['c3b'][$i] != 0) ? round($table['c3bg'][$i] * 100 / $table['c3b'][$i] , 2) : 0}}</td>
                        <td>{{$table['l1'][$i]}}</td>
                        <td>{{$table['l3'][$i]}}</td>
                        <td>{{$table['l6'][$i]}}</td>
                        <td>{{$table['l8'][$i]}}</td>
                        <td>{{($table['c3bg'][$i] != 0) ? round($table['l1'][$i] * 100 / $table['c3bg'][$i] , 2) : 0}}</td>
                        <td>{{($table['c3bg'][$i] != 0) ? round($table['l3'][$i] * 100 / $table['c3bg'][$i] , 2) : 0}}</td>
                        <td>{{($table['l1'][$i] != 0) ? round($table['l6'][$i] * 100 / $table['l1'][$i] , 2) : 0}}</td>
                        <td>{{($table['l1'][$i] != 0) ? round($table['l8'][$i] * 100 / $table['l1'][$i] , 2) : 0}}</td>
                    </tr>

            @endforeach

            <tr>
                <th></th>
                <th>Total</th>
                <th>{{$array_sum['c3']}}</th>
                <th>{{$array_sum['c3b']}}</th>
                <th>{{$array_sum['c3bg']}}</th>
                <th>{{$array_sum['c3b'] != 0 ? round($array_sum['c3bg'] * 100 / $array_sum['c3b'] , 2) : 0}}</th>
                <th>{{$array_sum['l1']}}</th>
                <th>{{$array_sum['l3']}}</th>
                <th>{{$array_sum['l6']}}</th>
                <th>{{$array_sum['l8']}}</th>
                <th>{{$array_sum['c3bg'] != 0 ? round($array_sum['l1'] * 100 / $array_sum['c3bg'] , 2) : 0}}</th>
                <th>{{$array_sum['c3bg'] != 0 ? round($array_sum['l3'] * 100 / $array_sum['c3bg'] , 2) : 0}}</th>
                <th>{{$array_sum['l1']  != 0 ? round($array_sum['l6'] * 100 / $array_sum['l1'] , 2) : 0}}</th>
                <th>{{$array_sum['l1']  != 0 ? round($array_sum['l8'] * 100 / $array_sum['l1'] , 2) : 0}}</th>
            </tr>
            </tbody>
        </table>
    </article>
</div>

