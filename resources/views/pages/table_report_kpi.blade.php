<table id="table_kpi" class="table table-hover table-condensed">
    <thead>
    <tr>
        <th colspan="6" class="border-bold-right index6"></th>

        @for ($i = 1; $i <= $days; $i++)
            <th colspan="2" style="text-align: center;" class="border-bold-right index5"> {{$i < 10 ? '0'.$i.$month : $i.$month}} </th>
        @endfor

    </tr>
    <tr>
        <th class="no-border-right index6" style="border-left: none;"></th>
        <th class="no-border-right index6">Marketer</th>
        <th class="border-bold-right index6" style="border-left: none;"></th>
        <th class="border-bold-right index6">KPI</th>
        <th class="border-bold-right index6">Actual</th>
        <th class="border-bold-right index6">GAP</th>

        @for ($i = 1; $i <= $days; $i++)
            <th class="index5">KPI</th>
            <th class="border-bold-right index5">Act</th>
        @endfor

    </tr>
    </thead>
    <tbody>
    @if(isset($data_maketer['total']))
    <tr style="font-weight: bold; color: #3276b1; font-size: medium;">
        <td class="no-border-right index3" style="border-left: none;"></td>
        <td class="no-border-right index3"><span>Total</span></td>
        <td class="border-bold-right index3"></td>
        <?php
            $total_kpi = @$data_maketer['total']['total_kpi'] ? $data_maketer['total']['total_kpi'] : 0;
            $total_actual = @$data_maketer['total']['total_actual'] ? $data_maketer['total']['total_actual'] : 0;
            $total_gap = @$kpi_selection == "c3b_cost" ? ($total_kpi - $total_actual) : ($total_actual - $total_kpi) ;
        ?>
        <td class="border-bold-right total_text index3">{{ $total_kpi.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
        <td class="border-bold-right total_text index3" id="act">
            {{ $total_actual.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
            <div class="hidden details_total index4">
                {{@$kpi_selection == 'c3b_cost' ? "spent: ".array_sum(@$data_maketer['total']['spent'] ? $data_maketer['total']['spent'] : array()).
                "\rc3b: ".array_sum(@$data_maketer['total']['c3b'] ? $data_maketer['total']['c3b'] : array()) :
                 (@$kpi_selection == 'l3_c3bg' ? "l3: ".array_sum(@$data_maketer['total']['l3'] ? $data_maketer['total']['l3'] : array()).
                 "\rc3bg: ".array_sum(@$data_maketer['total']['c3bg'] ? $data_maketer['total']['c3bg'] : array()) : "")}}
            </div>
        </td>
        <td class="border-bold-right total_text index3">{{ $total_gap.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>

        @for ($i = 1; $i <= $days; $i++)
            <td>{{ (@$data_maketer['total']['kpi'][$i] ? $data_maketer['total']['kpi'][$i] : 0).(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
            <td class="border-bold-right act" id="act">
                {{ (@$data_maketer['total']['actual'][$i] ? $data_maketer['total']['actual'][$i] : 0).(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                <div class="hidden details">
                    {{@$kpi_selection == 'c3b_cost' ? "spent: ".@$data_maketer['total']['spent'][$i]."\rc3b: ".@$data_maketer['total']['c3b'][$i] :
                     (@$kpi_selection == 'l3_c3bg' ? "l3: ".@$data_maketer['total']['l3'][$i]."\rc3bg: ".@$data_maketer['total']['c3bg'][$i] : "")}}
                </div>
            </td>
        @endfor
    </tr>
    @endif
    @foreach($data_maketer as $user => $item)
        @if($user == "total") @continue @endif
        <tr data-tt-id="{{@$user}}">
            <?php $gap = @$kpi_selection == "c3b_cost" ? @$item['total_kpi'] - @$item['total_actual'] : @$item['total_actual'] - @$item['total_kpi'] ?>
            <td class="no-border-right index3" style="border-left: none;">
                <a href="javascript:void(0)" title="Show detail" class="channel__show"><i class="fa fa-plus-circle"></i></a>
                <a href="javascript:void(0)" title="Hide detail" class="channel__hide hidden"><i class="fa fa-minus-circle"></i></a>
            </td>

            <td class="no-border-right index3" style="text-align: left;">
                <span style="font-weight: bold">{{ @$user }}</span>
            </td>

            <td class="border-bold-right index3">
                <a class=' btn-xs btn-default edit_kpi' data-user-id="{{@$item['user_id']}}"
                   href="" data-toggle="modal" data-target="#addModal" onclick="set_user_id(this)"
                   data-original-title='Edit Row'><i
                            class='fa fa-pencil'></i></a>
            </td>

            <td class="border-bold-right total_text index3">
                {{ @$item['total_kpi'].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
            </td>
            @if($gap < 0)
                <td class="border-bold-right total_text gap_text index3" id="act">
                    {{ @$item['total_actual'].(@$kpi_selection == 'l3_c3bg' ? '%' : '')  }}
                    <div class="hidden details_total index4">
                        {{@$kpi_selection == 'c3b_cost' ? "spent: ".array_sum(@$item['spent'] ? $item['spent'] : array()).
                        "\rc3b: ".array_sum(@$item['c3b'] ? $item['c3b'] : array()) :
                         (@$kpi_selection == 'l3_c3bg' ? "l3: ".array_sum(@$item['l3'] ? $item['l3'] : array()).
                         "\rc3bg: ".array_sum(@$item['c3bg'] ? $item['c3bg'] : array()) : "")}}
                    </div>
                </td>
                <td class="border-bold-right total_text gap_text index3">{{ $gap.(@$kpi_selection == 'l3_c3bg' ? '%' : '')  }}</td>
            @else
                <td class="border-bold-right total_text index3" id="act">
                    {{ @$item['total_actual'].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                    <div class="hidden details_total index4">
                        {{@$kpi_selection == 'c3b_cost' ? "spent: ".array_sum(@$item['spent'] ? $item['spent'] : array()).
                        "\rc3b: ".array_sum(@$item['c3b'] ? $item['c3b'] : array()) :
                         (@$kpi_selection == 'l3_c3bg' ? "l3: ".array_sum(@$item['l3'] ? $item['l3'] : array()).
                         "\rc3bg: ".array_sum(@$item['c3bg'] ? $item['c3bg'] : array()) : "")}}
                    </div>
                </td>
                <td class="border-bold-right total_text index3">{{ $gap.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
            @endif

            @for ($i = 1; $i <= $days; $i++)
                <?php $gap_day = @$kpi_selection == "c3b_cost" ? @$item['kpi'][$i] - @$item['actual'][$i] : @$item['actual'][$i] - @$item['kpi'][$i] ?>
                <td>{{ (@$item['kpi'][$i] ? @$item['kpi'][$i] : 0).(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
                @if(@$gap_day < 0)
                    <td class="border-bold-right act gap_text" id="act">
                        {{ (@$item['actual'][$i] ? @$item['actual'][$i] : 0).(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                        <div class="hidden details">
                            {{@$kpi_selection == 'c3b_cost' ? "spent: ".@$item['spent'][$i]."\rc3b: ".@$item['c3b'][$i] :
                             (@$kpi_selection == 'l3_c3bg' ? "l3: ".@$item['l3'][$i]."\rc3bg: ".@$item['c3bg'][$i] : "")}}
                        </div>
                    </td>
                @else
                    <td class="border-bold-right" id="act">
                        {{ (@$item['actual'][$i] ? @$item['actual'][$i] : 0).(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                        <div class="hidden details">
                            {{@$kpi_selection == 'c3b_cost' ? "spent: ".@$item['spent'][$i]."\rc3b: ".@$item['c3b'][$i] :
                             (@$kpi_selection == 'l3_c3bg' ? "l3: ".@$item['l3'][$i]."\rc3bg: ".@$item['c3bg'][$i] : "")}}
                        </div>
                    </td>
                @endif
            @endfor
        </tr>
        @if(isset($item['channels']))
            @foreach($item['channels'] as $key => $value)
                <?php $gap = $kpi_selection == "c3b_cost" ? ($value['total_kpi'] - $value['total_actual']) :
                    ($value['total_actual'] - $value['total_kpi']) ?>
                <tr data-tt-parent-id="{{$user}}" class="hidden" id="{{$key}}">
                    <td class="no-border-right index3" style="border-left: none;"></td>
                    <td class="no-border-right index3" style="text-align: left;"><span>{{$key}}</span></td>
                    <td class="border-bold-right index3"></td>
                    <td class="border-bold-right total_text index3">{{ $value['total_kpi'].(@$kpi_selection == 'l3_c3bg' ? '%' : '')  }}</td>
                    @if($gap < 0)
                        <td class="border-bold-right total_text gap_text index3" id="act">
                            {{ $value['total_actual'].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                            <div class="hidden details_total index4">
                                {{@$kpi_selection == 'c3b_cost' ? "spent: ".array_sum(@$value['spent'] ? $value['spent'] : array()).
                                "\rc3b: ".array_sum(@$value['c3b'] ? $value['c3b'] : array()) :
                                 (@$kpi_selection == 'l3_c3bg' ? "l3: ".array_sum(@$value['l3'] ? $value['l3'] : array()).
                                 "\rc3bg: ".array_sum(@$value['c3bg'] ? $value['c3bg'] : array()) : "")}}
                            </div>
                        </td>
                        <td class="border-bold-right total_text gap_text index3">{{ $gap.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
                    @else
                        <td class="border-bold-right total_text index3" id="act">
                            {{ $value['total_actual'].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                            <div class="hidden details_total index4">
                                {{@$kpi_selection == 'c3b_cost' ? "spent: ".array_sum(@$value['spent'] ? $value['spent'] : array()).
                                "\rc3b: ".array_sum(@$value['c3b'] ? $value['c3b'] : array()) :
                                 (@$kpi_selection == 'l3_c3bg' ? "l3: ".array_sum(@$value['l3'] ? $value['l3'] : array()).
                                 "\rc3bg: ".array_sum(@$value['c3bg'] ? $value['c3bg'] : array()) : "")}}
                            </div>
                        </td>
                        <td class="border-bold-right total_text index3">{{ $gap.(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}</td>
                    @endif

                    @for ($i = 1; $i <= $days; $i++)
                        <?php
                            $value['kpi'][$i] = isset($value['kpi'][$i]) ? $value['kpi'][$i] : 0;
                            $value['actual'][$i] = isset($value['actual'][$i]) ? $value['actual'][$i] : 0;
                            $gap_day = $kpi_selection == "c3b_cost" ? ($value['kpi'][$i] - $value['actual'][$i]) :
                                ($value['actual'][$i] - $value['kpi'][$i]);
                        ?>
                        <td>{{ $value['kpi'][$i].(@$kpi_selection == 'l3_c3bg' ? '%' : '')  }}</td>
                        @if($gap_day < 0)
                            <td class="border-bold-right gap_text" id="act">
                                {{ $value['actual'][$i].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                                <div class="hidden details">
                                    {{@$kpi_selection == 'c3b_cost' ? "spent: ".@$value['spent'][$i]."\rc3b: ".@$value['c3b'][$i] :
                                     (@$kpi_selection == 'l3_c3bg' ? "l3: ".@$value['l3'][$i]."\rc3bg: ".@$value['c3bg'][$i] : "")}}
                                </div>
                            </td>
                        @else
                            <td class="border-bold-right" id="act">
                                {{ $value['actual'][$i].(@$kpi_selection == 'l3_c3bg' ? '%' : '') }}
                                <div class="hidden details">
                                    {{@$kpi_selection == 'c3b_cost' ? "spent: ".@$value['spent'][$i]."\rc3b: ".@$value['c3b'][$i] :
                                     (@$kpi_selection == 'l3_c3bg' ? "l3: ".@$value['l3'][$i]."\rc3bg: ".@$value['c3bg'][$i] : "")}}
                                </div>
                            </td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>
