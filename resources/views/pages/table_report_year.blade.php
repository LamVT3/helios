<div class="widget-body no-padding">
    <?php $usd_vnd = $reportY['config']['USD_VND'] ?>
    <?php $usd_thb = $reportY['config']['USD_THB'] ?>
    <?php

    unset($reportY['config']);
    $reportY['Total'] = (object)[
        'c1' => 0,
        'c2' => 0,
        'c3' => 0,
        'c3b' => 0,
        'c3bg' => 0,
        'spent' => 0,
        'l1' => 0,
        'l3' => 0,
        'l6' => 0,
        'l8' => 0,
        'revenue' => 0,
    ];
        foreach ($reportY as $rs) {
            $reportY['Total']->c1 += isset($rs->c1) ? $rs->c1 : 0;
            $reportY['Total']->c2 += isset($rs->c2) ? $rs->c2 : 0;
            $reportY['Total']->c3 += isset($rs->c3) ? $rs->c3 : 0;
            $reportY['Total']->c3b += isset($rs->c3b) ? $rs->c3b : 0;
            $reportY['Total']->c3bg += isset($rs->c3bg) ? $rs->c3bg : 0;
            $reportY['Total']->spent += isset($rs->spent) ? $rs->spent : 0;
            $reportY['Total']->l1 += isset($rs->l1) ? $rs->l1 : 0;
            $reportY['Total']->l3 += isset($rs->l3) ? $rs->l3 : 0;
            $reportY['Total']->l6 += isset($rs->l6) ? $rs->l6 : 0;
            $reportY['Total']->l8 += isset($rs->l8) ? $rs->l8 : 0;
            $reportY['Total']->revenue += isset($rs->revenue) ? $rs->revenue : 0;
        }
    ?>
    <div style="margin: 20px auto;">
        <table class="table" width="100%">
            <tr>
                <div style="font-size: xx-large; margin: 20px auto;" class="bold center blue">MARKETING_INDEX REPORT</div>
            </tr>
            <tr style="font-size: x-large;" class="bold italic blue">
                <td>Budget :</td>
                <td class="orange"></td>
                <td>Target L1 :</td>
                <td class="orange"></td>
                <td>L3/C3B :</td>
                <td class="orange"></td>
            </tr>
            <tr style="font-size: large" class="bold blue">
                <td>Spent :</td>
                <td class="orange">{{ $reportY['Total']->spent }} USD</td>
                <td>Produced (L1) :</td>
                <td class="orange">{{ $reportY['Total']->l1 }} </td>
                <td>Actual (L3/C3BG) : </td>
                <td class="orange">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
            </tr>
        </table>
    </div>
    <div class="wrapper_report_year gray">
        <table id="table_report" class="table" width="100%">
            <thead>
                <tr class="font-medium">
                    <th></th>
                    <th></th>
                    <?php
                        $arrY = array();
                        $col = 1;
                        foreach($reportY as $key => $rs) {
                            $y = substr($key, 0, 4);
                            if(!in_array($y, $arrY)){
                                if(!empty($arrY)){
                                    echo '<th colspan="'.$col.'" class="center" style="border: 1px solid #A0A0A0; border-bottom:none;">'.end($arrY).'</th>';
                                }
                                $col = 1;
                                array_push($arrY, $y);
                            } else {
                                $col += 1;
                            }
                        }
                    ?>
                    <th class="center" style="border: 1px solid #A0A0A0; border-bottom:none;border-right:none;">Total</th>
                </tr>
                <tr class="italic center">
                    <th></th>
                    <th></th>
                    <?php
                        $arrY = array();
                        foreach($reportY as $key => $rs) {
                            if($key != "Total"){
                                $m = substr($key, -2);
                                echo '<th class="center">'.$m.'</th>';
                            }
                        }
                    ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold font-medium background-blue white">BUDGET</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>ME/RE %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->revenue != 0) ? round($rs->spent * $usd_thb / $rs->revenue,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->revenue != 0) ? round($reportY['Total']->spent * $usd_thb / $reportY['Total']->revenue,4)*100 : 0 }}%</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td>ME (USD)</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->spent}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->spent }}</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td>RE (THB)</td>@foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->revenue}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->revenue }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">C3B</td>@foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3b != 0) ? round($rs->spent * $usd_vnd / $rs->c3b) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->c3b) : 0 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">C3BG</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3bg != 0) ? round($rs->spent * $usd_vnd / $rs->c3bg) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->c3bg) : 0 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L1 used</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l1 != 0) ? round($rs->spent * $usd_vnd / $rs->l1) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l1 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l1) : 0 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L3</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l3 != 0) ? round($rs->spent * $usd_vnd / $rs->l3) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l3 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l3) : 0 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L6</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l6 != 0) ? round($rs->spent * $usd_vnd / $rs->l6) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l6 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l6) : 0 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L8</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l8 != 0) ? round($rs->spent * $usd_vnd / $rs->l8) : 0 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l8 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l8) : 0 }}</td>
                </tr>
                <tr>
                    <td class="bold font-medium background-blue white">QUANTITY</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>C3B</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->c3b }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->c3b }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>C3BG</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->c3bg }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->c3bg }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L1 used</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l1 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l1 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L3</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l3 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l3 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L6</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l6 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l6 }}</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L8</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l8 }}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l8 }}</td>
                </tr>
                <tr>
                    <td class="bold font-medium background-blue white">QUALITY</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>L3/C3B %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3b != 0) ? round($rs->l3 / $rs->c3b,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3b,4)*100 : 0 }}%</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L3/C3BG %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3bg != 0) ? round($rs->l3 / $rs->c3bg,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L3/L1 %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l1 != 0) ? round($rs->l3 / $rs->l1,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l1 != 0) ? round($reportY['Total']->l3 / $reportY['Total']->l1,4)*100 : 0 }}%</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L1/C3BG %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3bg != 0) ? round($rs->l1 / $rs->c3bg,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l1 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">C3BG/C3B %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->c3b != 0) ? round($rs->c3bg / $rs->c3b,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->c3bg / $reportY['Total']->c3b,4)*100 : 0 }}%</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>Return Ratio</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>Duplicate Ratio</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L6/L3 %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l3 != 0) ? round($rs->l6 / $rs->l3,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l3 != 0) ? round($reportY['Total']->l6 / $reportY['Total']->l3,4)*100 : 0 }}%</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L8/L6 %</td>
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l6 != 0) ? round($rs->l8 / $rs->l6,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l6 != 0) ? round($reportY['Total']->l8 / $reportY['Total']->l6,4)*100 : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<style>
    tr:nth-child(even) {
        background: #fafafa
    }
    tr:nth-child(odd) {
        background: #ffffff
    }
    col:first-child {
        background: #2ea8e5
    }
    #table_report th {
        vertical-align:middle
    }

    .italic {
        font-style: italic;
    }
    .bold {
        font-weight: bold;
    }
    .center {
        text-align: center;
    }
    .orange {
        color: #ED8515;
    }
    .blue {
        color: #157DEC;
    }
    .white {
        color: #fafafa;
    }
    .gray {
        color: #505050;
    }
    .yellow {
        color: #FFFF33;
    }
    .background-blue {
        background-color: #157DEC;
    }
    .font-medium {
        font-size: medium;
    }
</style>