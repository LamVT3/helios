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
    <div class="wrapper_report_year gray">
        <div style="width: 250px; display: inline-block;">
            <table class="table" width="100%">
                <thead style="top: 34px;">
                <tr class="font-medium orange" style="height: 36px;">
                    <th style="border: 2px solid #fff; border-left: none; text-align: right;">Year</th>
                </tr>
                <tr class="italic center" style="height: 36px;">
                    <th style="border-right: 2px solid #fff; border-bottom: 2px solid #fff; text-align: right;">Month</th>
                </tr>
                <tr class="italic center" style="height: 53px;">
                    <th style="border-right: 2px solid #fff; text-align: right; vertical-align: middle;"> Days in month</th>
                </tr>
                </thead>
                <tbody>
                <tr class="bold font-medium blue">
                    <td>Spent</td>
                </tr>
                <tr class="italic">
                    <td>No C3 produced/ngày</td>
                </tr>
                <tr class="italic">
                    <td>No C3 produced/tháng</td>
                </tr>
                <tr class="bold">
                    <td class="center">No of C3B transfered</td>
                </tr>
                <tr class="bold">
                    <td class="center">No of L3</td>
                </tr>
                <tr class="bold">
                    <td class="center">No of L1</td>
                </tr>
                <tr class="bold">
                    <td class="center">Price of C3B produced (VND)</td>
                </tr>
                <tr class="bold">
                    <td class="center">L3/C3B transfered</td>
                </tr>
                <tr class="bold">
                    <td class="center">L3/L1</td>
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
                <tr class="bold font-medium blue">
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->spent}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->spent }}</td>
                </tr>
                <tr class="italic">
                    <?php
                    $totalDays = 0;
                    foreach ($reportY as $key => $rs) {
                        if ($key != "Total") {
                            $y = substr($key, 0, 4);
                            $m = substr($key, -2);
                            $days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                            $totalDays += $days;
                            echo '<td class="center">'.round($rs->c3b/$days).'</td>';
                        }
                    }
                    ?>
                    <td class="center">{{ round($reportY['Total']->c3b/$totalDays) }}</td>
                </tr>
                <tr class="italic">
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
                            <td class="center">{{ $rs->c3bg}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->c3bg}}</td>
                </tr>
                <tr class="bold">
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l3}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l3}}</td>
                </tr>
                <tr class="bold">
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ $rs->l1}}</td>
                        @endif
                    @endforeach
                    <td class="center">{{ $reportY['Total']->l1}}</td>
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
                            <td class="center">{{ ($rs->c3bg != 0) ? round($rs->l3 / $rs->c3bg,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->c3bg != 0) ? round($reportY['Total']->l3 / $reportY['Total']->c3bg,4)*100 : 0 }}%</td>
                </tr>
                <tr class="bold">
                    @foreach($reportY as $key => $rs)
                        @if($key != "Total")
                            <td class="center">{{ ($rs->l1 != 0) ? round($rs->l3 / $rs->l1,4)*100 : 0 }}%</td>
                        @endif
                    @endforeach
                    <td class="center">{{ ($reportY['Total']->l1 != 0) ? round($reportY['Total']->l3 / $reportY['Total']->l1,4)*100 : 0 }}%</td>
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
