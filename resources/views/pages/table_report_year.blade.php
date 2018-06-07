<div class="widget-body no-padding">
    <?php $usd_vnd = $reportY['config']['USD_VND'] ?>
    <?php $usd_thb = $reportY['config']['USD_THB'] ?>
    <?php
        unset($reportY['config']);

        $c1 = $c2 = $c3 = $c3b = $c3bg = $spent = $l1 = $l3 = $l6 = $l8 = $revenue = 0;
        foreach ($reportY as $rs) {
            $c1 += isset($rs->c1) ? $rs->c1 : 0;
            $c2 += isset($rs->c2) ? $rs->c2 : 0;
            $c3 += isset($rs->c3) ? $rs->c3 : 0;
            $c3b += isset($rs->c3b) ? $rs->c3b : 0;
            $c3bg += isset($rs->c3bg) ? $rs->c3bg : 0;
            $spent += isset($rs->spent) ? $rs->spent : 0;
            $l1 += isset($rs->l1) ? $rs->l1 : 0;
            $l3 += isset($rs->l3) ? $rs->l3 : 0;
            $l6 += isset($rs->l6) ? $rs->l6 : 0;
            $l8 += isset($rs->l8) ? $rs->l8 : 0;
            $revenue += isset($rs->revenue) ? $rs->revenue : 0;
        }
        $reportY['Total'] = (object)[
            'c1' => $c1,
            'c2' => $c2,
            'c3' => $c3,
            'c3b' => $c3b,
            'c3bg' => $c3bg,
            'spent' => $spent,
            'l1' => $l1,
            'l3' => $l3,
            'l6' => $l6,
            'l8' => $l8,
            'revenue' => $revenue,
        ];
    ?>
    <div style="font-size: xx-large; border-bottom: 1px solid #ddd !important; padding: 15px 0; background: #fafafa" class="bold center orange">RADAR MARKETING ONLINE</div>
    <div style="font-size: x-large; border-bottom: 1px #ddd solid !important; padding: 15px 0; float: left; width: 100%" class="bold italic blue">
        <div class="inlineBlock col-md-2">Budget :</div><!--
        --><div class="orange inlineBlock col-md-2"></div><!--
        --><div class="inlineBlock col-md-2">Target L1 :</div><!--
        --><div class="orange inlineBlock col-md-2"></div><!--
        --><div class="inlineBlock col-md-2">L3/C3B :</div><!--
        --><div class="orange inlineBlock col-md-2"></div>
    </div>
    <div style="font-size: x-large; padding: 15px 0; float: left; width: 100%; " class="bold blue">
        <div class="inlineBlock col-md-2">Spent :</div><!--
        --><div class="orange inlineBlock col-md-2">{{ $reportY['Total']->spent }} USD</div><!--
        --><div class="inlineBlock col-md-2">Produced :</div><!--
        --><div class="orange inlineBlock col-md-2">{{ $reportY['Total']->l1 }} </div><!--
        --><div class="inlineBlock col-md-2">Actual : </div><!--
        --><div class="orange inlineBlock col-md-2">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3bg,4)*100 : 0 }}%</div>
    </div>
    <div class="wrapper_report_year gray">
        <div style="width: 250px; display: inline-block;">
            <table class="table" width="100%">
                <thead style="top: 34px;">
                <tr class="font-medium orange" style="height: 36px;">
                    <th style="border: 2px solid #fff; border-left: none; text-align: right;" colspan="2">Year</th>
                </tr>
                <tr class="italic center" style="height: 36px;">
                    <th style="border-right: 2px solid #fff; border-bottom: 2px solid #fff; text-align: right;" colspan="2">Month</th>
                </tr>
                <tr class="italic center" style="height: 53px;">
                    <th style="border-right: 2px solid #fff; text-align: right; vertical-align: middle;" colspan="2"> Days in month</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="bold font-medium bg-blue white" style="height: 40px;">BUDGET</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>ME/RE %</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td>ME (USD)</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td>RE (THB)</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">C3B</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">C3BG</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L1 used</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L3</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L6</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td class="center">L8</td>
                </tr>
                <tr>
                    <td class="bold font-medium bg-blue white" style="height: 40px;">QUANTITY</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>C3B</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>C3BG</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L1 used</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L3</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L6</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L8</td>
                </tr>
                <tr>
                    <td class="bold font-medium bg-blue white" style="height: 40px;">QUALITY</td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange">Actual</td>
                    <td>L3/C3B %</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L3/C3BG %</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L3/L1 %</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">L1/C3BG %</td>
                </tr>
                <tr class="italic">
                    <td></td>
                    <td class="center">C3BG/C3B %</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>Return Ratio</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>Duplicate Ratio</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L6/L3 %</td>
                </tr>
                <tr class="bold">
                    <td></td>
                    <td>L8/L6 %</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="content" style="width: calc(100% - 250px); float: right">
            <table id="table_report" class="table" width="100%">
                <thead style="top: 34px; z-index: 0;">
                    <tr class="font-medium orange" style="height: 36px;">
                        <?php
                            $arrY = array();
                            $col = 1;
                            foreach($reportY as $key => $rs) {
                                $y = substr($key, 0, 4);
                                if(!in_array($y, $arrY)){
                                    if(!empty($arrY)){
                                        echo '<th colspan="'.$col.'" class="center" style="border: 2px solid #fff; border-left:none;">'.end($arrY).'</th>';
                                    }
                                    $col = 1;
                                    array_push($arrY, $y);
                                } else {
                                    $col += 1;
                                }
                            }
                        ?>
                        <th class="center" style="border-top: 2px solid #fff; border-bottom: 1px solid #fff;">Total</th>
                    </tr>
                    <tr class="italic center" style="height: 36px;">
                        <?php
                        $arrY = array();
                        foreach ($reportY as $key => $rs) {
                            if ($key != "Total") {
                                $y = substr($key, 0, 4);
                                $m = substr($key, -2);
                                echo '<th style="border-bottom: 2px solid #fff;'.(($m == '12' || ($m == date('m') && $y == date('Y'))) ? "border-right: 2px solid #fff;" : "").
                                    '" class="center '.(($m == date('m') && $y == date('Y')) ? 'white bg-blue' : '').'">'.$m.'</th>';
                            }
                        }
                        ?>
                        <th class="center" style="border-top: 2px solid #fff; border-bottom: 2px solid #fff;"></th>
                    </tr>
                    <tr class="italic center" style="height: 53px;">
                        <?php
                        $arrY = array();
                        foreach ($reportY as $key => $rs) {
                            if ($key != "Total") {
                                $y = substr($key, 0, 4);
                                $m = substr($key, -2);
                                $days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                                echo '<th style="'.(($m == '12' || ($m == date('m') && $y == date('Y'))) ? "border-right: 2px solid #fff;" : "").
                                    '" class="center '.(($m == date('m') && $y == date('Y')) ? 'white bg-blue' : '').'">'.$days.' days</th>';
                            }
                        }
                        ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bold font-medium bg-blue white" style="height: 41px;">
                    </tr>
                    <tr class="bold font-medium blue">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->revenue != 0) ? round($rs->spent * $usd_thb / $rs->revenue,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->revenue != 0) ? round($reportY['Total']->spent * $usd_thb / $reportY['Total']->revenue,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->spent}}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->spent }}</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->revenue}}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->revenue }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3b != 0) ? round($rs->spent * $usd_vnd / $rs->c3b) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->c3b) : 0 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3bg != 0) ? round($rs->spent * $usd_vnd / $rs->c3bg) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->c3bg) : 0 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l1 != 0) ? round($rs->spent * $usd_vnd / $rs->l1) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l1 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l1) : 0 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l3 != 0) ? round($rs->spent * $usd_vnd / $rs->l3) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l3 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l3) : 0 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l6 != 0) ? round($rs->spent * $usd_vnd / $rs->l6) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l6 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l6) : 0 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l8 != 0) ? round($rs->spent * $usd_vnd / $rs->l8) : 0 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l8 != 0) ? round($reportY['Total']->spent * $usd_vnd / $reportY['Total']->l8) : 0 }}</td>
                    </tr>
                    <tr class="bold font-medium bg-blue white" style="height: 40px;">
                    </tr>
                    <tr class="bold font-medium blue">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->c3b }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->c3b }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->c3bg }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->c3bg }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->l1 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->l1 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->l3 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->l3 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->l6 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->l6 }}</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ $rs->l8 }}</td>
                            @endif
                        @endforeach
                        <td class="center">{{ $reportY['Total']->l8 }}</td>
                    </tr>
                    <tr class="bold font-medium bg-blue white" style="height: 40px;">
                    </tr>
                    <tr class="bold font-medium blue">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3b != 0) ? round($rs->l3 / $rs->c3b,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3b,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3bg != 0) ? round($rs->l3 / $rs->c3bg,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l1 != 0) ? round($rs->l3 / $rs->l1,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l1 != 0) ? round($reportY['Total']->l3 / $reportY['Total']->l1,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3bg != 0) ? round($rs->l1 / $rs->c3bg,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l1 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="italic">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->c3b != 0) ? round($rs->c3bg / $rs->c3b,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->c3b != 0) ? round($reportY['Total']->c3bg / $reportY['Total']->c3b,4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            <td class="center">0</td>
                        @endforeach
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            <td class="center">0</td>
                        @endforeach
                    </tr>
                    <tr class="bold">
                        @foreach($reportY as $key => $rs)
                            @if($key != "Total")
                                <td class="center">{{ ($rs->l3 != 0) ? round($rs->l6 / $rs->l3,4)*100 : 0 }}%</td>
                            @endif
                        @endforeach
                        <td class="center">{{ ($reportY['Total']->l3 != 0) ? round($reportY['Total']->l6 / $reportY['Total']->l3, 4)*100 : 0 }}%</td>
                    </tr>
                    <tr class="bold">
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
</div>

<style>
    tr:nth-child(even) {
        background: #fafafa;
    }
    tr:nth-child(odd) {
        background: #ffffff;
    }
    col:first-child {
        background: #2ea8e5;
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
        color: #ffffff;
    }
    .gray {
        color: #505050;
    }
    .yellow {
        color: #ffff33;
    }
    .bg-blue {
        background: #157DEC;
    }
    .font-medium {
        font-size: medium;
    }
    .inlineBlock {
        display: inline-block;
    }
    .table > tbody > tr > td {
        min-width: 30px;
        height: 40px;
        border-bottom: 1px solid #ddd;
    }
    .content {
        border: none 0px;
        overflow-x: scroll;
    }
    .sticky {
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>
