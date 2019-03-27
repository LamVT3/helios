@foreach ($array_ad as $i)
        <tr class="ads_detail ads_show">
            <td class='border-right_none'></td>
            <td>{{$i}}</td>
            <td>{{$table['c3'][$i]}}</td>
            <td>{{$table['c3b'][$i]}}</td>
            <td>{{$table['c3bg'][$i]}}</td>
            <td>{{($table['c3b'][$i] != 0) ? round($table['c3bg'][$i] * 100 / $table['c3b'][$i] , 2) : 0}}</td>
            @if ($type == 'TOA')
                <td>{{round($table['spent'][$i], 2)}}</td>
            @endif
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

