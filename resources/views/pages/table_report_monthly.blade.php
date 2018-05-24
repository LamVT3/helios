<div class="widget-body no-padding">
    <?php $usd_vnd = $report['config']['USD_VND'] ?>
    <?php $usd_thb = $report['config']['USD_THB'] ?>
    <div style="margin: 20px auto;">
        <table class="table" width="100%">
            <tr>
                <div style="text-align: center; font-weight: bold; font-size: xx-large; margin: 20px auto;">MARKETING_INDEX REPORT</div>
            </tr>
            <tr style="font-weight: bold; font-size: x-large; font-style: italic;">
                <td>Budget :</td>
                <td></td>
                <td>Target L1 :</td>
                <td></td>
                <td>L3/C3B :</td>
                <td></td>
            </tr>
            <tr style="font-weight: bold; font-size: large">
                <td>Spent :</td>
                <td>{{ $report['total']->spent }} USD</td>
                <td>Produced (L1) :</td>
                <td>{{ $report['total']->l1 }} </td>
                <td>Actual (L3/C3B) : </td>
                <td>{{ ($report['total']->c3b != 0) ? round($report['total']->l3 / $report['total']->c3b,4)*100 : 0 }}%</td>
            </tr>
        </table>
    </div>
    <div class="wrapper_report_monthly">
        <table id="table_report" class="table" width="100%">
            <thead>
                <tr style="font-size: medium">
                    <th></th>
                    <th></th>
                    <th>Week 1</th>
                    <th>Week 2</th>
                    <th>Week 3</th>
                    <th>Week 4</th>
                    @if($report['week5']->range != NULL)
                    <th>Week 5</th>
                    @endif
                    @if($report['week6']->range != NULL)
                    <th>Week 6</th>
                    @endif
                    <th>Total</th>
                    <th>Range Date</th>
                    <th>Forecast</th>
                </tr>
                <tr style="font-style: italic;">
                    <th></th>
                    <th></th>
                    <th>{{ $report['week1']->range }}</th>
                    <th>{{ $report['week2']->range }}</th>
                    <th>{{ $report['week3']->range }}</th>
                    <th>{{ $report['week4']->range }}</th>
                    @if($report['week5']->range != NULL)
                    <th>{{ $report['week5']->range }}</th>
                    @endif
                    @if($report['week6']->range != NULL)
                    <th>{{ $report['week6']->range }}</th>
                    @endif
                    <th>{{ $report['total']->range }}</th>
                    <th>
                        <div id="rangedate" class="pull-left"
                             style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc;/* margin: 10px 15px*/">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span class="rangedate"></span> <b class="caret"></b>
                        </div>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold; background-color: #2ea8e5; font-size: medium">BUDGET</td>
                </tr>
                <tr style="font-weight: bold; font-size: medium">
                    <td style="background-color: #ba871f">Actual</td>
                    <td>ME/RE %</td>
                    <td>{{ ($report['week1']->revenue != 0) ? round($report['week1']->spent * $usd_thb / $report['week1']->revenue,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->revenue != 0) ? round($report['week2']->spent * $usd_thb / $report['week2']->revenue,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->revenue != 0) ? round($report['week3']->spent * $usd_thb / $report['week3']->revenue,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->revenue != 0) ? round($report['week4']->spent * $usd_thb / $report['week4']->revenue,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->revenue != 0) ? round($report['week5']->spent * $usd_thb / $report['week5']->revenue,4)*100 : 0 }}%</td>
                    @endif
                    @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->revenue != 0) ? round($report['week6']->spent * $usd_thb / $report['week6']->revenue,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->revenue != 0) ? round($report['total']->spent * $usd_thb / $report['total']->revenue,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->revenue != 0) ? round($report['rangeDate']->spent * $usd_thb / $report['rangeDate']->revenue,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td>ME (USD)</td>
                    <td>{{ $report['week1']->spent }}</td>
                    <td>{{ $report['week2']->spent }}</td>
                    <td>{{ $report['week3']->spent }}</td>
                    <td>{{ $report['week4']->spent }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->spent }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ $report['week6']->spent }}</td>
                    @endif
                    <td>{{ $report['total']->spent }}</td>
                    <td>{{ $report['rangeDate']->spent }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td>RE (THB)</td>
                    <td>{{ $report['week1']->revenue }}</td>
                    <td>{{ $report['week2']->revenue }}</td>
                    <td>{{ $report['week3']->revenue }}</td>
                    <td>{{ $report['week4']->revenue }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->revenue }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ $report['week6']->revenue }}</td>
                    @endif
                    <td>{{ $report['total']->revenue }}</td>
                    <td>{{ $report['rangeDate']->revenue }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">C3B</td>
                    <td>{{ ($report['week1']->c3b != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->c3b) : 0 }}</td>
                    <td>{{ ($report['week2']->c3b != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->c3b) : 0 }}</td>
                    <td>{{ ($report['week3']->c3b != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->c3b) : 0 }}</td>
                    <td>{{ ($report['week4']->c3b != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->c3b) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3b != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->c3b) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->c3b != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->c3b) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->c3b != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->c3b) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->c3b) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">C3BG</td>
                    <td>{{ ($report['week1']->c3bg != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->c3bg) : 0 }}</td>
                    <td>{{ ($report['week2']->c3bg != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->c3bg) : 0 }}</td>
                    <td>{{ ($report['week3']->c3bg != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->c3bg) : 0 }}</td>
                    <td>{{ ($report['week4']->c3bg != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->c3bg) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3bg != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->c3bg) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->c3bg != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->c3bg) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->c3bg != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->c3bg) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->c3bg) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">L1 used</td>
                    <td>{{ ($report['week1']->l1 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l1) : 0 }}</td>
                    <td>{{ ($report['week2']->l1 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l1) : 0 }}</td>
                    <td>{{ ($report['week3']->l1 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l1) : 0 }}</td>
                    <td>{{ ($report['week4']->l1 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l1) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l1 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l1) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->l1 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l1) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->l1 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l1) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->l1 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l1) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">L3</td>
                    <td>{{ ($report['week1']->l3 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l3) : 0 }}</td>
                    <td>{{ ($report['week2']->l3 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l3) : 0 }}</td>
                    <td>{{ ($report['week3']->l3 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l3) : 0 }}</td>
                    <td>{{ ($report['week4']->l3 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l3) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l3 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l3) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->l3 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l3) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->l3 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l3) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->l3 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l3) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">L6</td>
                    <td>{{ ($report['week1']->l6 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l6) : 0 }}</td>
                    <td>{{ ($report['week2']->l6 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l6) : 0 }}</td>
                    <td>{{ ($report['week3']->l6 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l6) : 0 }}</td>
                    <td>{{ ($report['week4']->l6 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l6) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l6 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l6) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->l6 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l6) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->l6 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l6) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->l6 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l6) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td style="text-align: center">L8</td>
                    <td>{{ ($report['week1']->l8 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l8) : 0 }}</td>
                    <td>{{ ($report['week2']->l8 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l8) : 0 }}</td>
                    <td>{{ ($report['week3']->l8 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l8) : 0 }}</td>
                    <td>{{ ($report['week4']->l8 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l8) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l8 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l8) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td>{{ ($report['week6']->l8 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l8) : 0 }}</td>
                    @endif
                    <td>{{ ($report['total']->l8 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l8) : 0 }}</td>
                    <td>{{ ($report['rangeDate']->l8 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l8) : 0 }}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: #2ea8e5; font-size: medium">QUANTITY</td>
                </tr>
                <tr style="font-weight: bold; font-size: medium">
                    <td style="background-color: #ba871f">Actual</td>
                    <td>C3B</td>
                    <td>{{ $report['week1']->c3b }}</td>
                    <td>{{ $report['week2']->c3b }}</td>
                    <td>{{ $report['week3']->c3b }}</td>
                    <td>{{ $report['week4']->c3b }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->c3b }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->c3b }}</td>
                    @endif
                    <td>{{ $report['total']->c3b }}</td>
                    <td>{{ $report['rangeDate']->c3b }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>C3BG</td>
                    <td>{{ $report['week1']->c3bg }}</td>
                    <td>{{ $report['week2']->c3bg }}</td>
                    <td>{{ $report['week3']->c3bg }}</td>
                    <td>{{ $report['week4']->c3bg }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->c3bg }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->c3bg }}</td>
                    @endif
                    <td>{{ $report['total']->c3bg }}</td>
                    <td>{{ $report['rangeDate']->c3bg }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L1 used</td>
                    <td>{{ $report['week1']->l1 }}</td>
                    <td>{{ $report['week2']->l1 }}</td>
                    <td>{{ $report['week3']->l1 }}</td>
                    <td>{{ $report['week4']->l1 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->l1 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->l1 }}</td>
                    @endif
                    <td>{{ $report['total']->l1 }}</td>
                    <td>{{ $report['rangeDate']->l1 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L3</td>
                    <td>{{ $report['week1']->l3 }}</td>
                    <td>{{ $report['week2']->l3 }}</td>
                    <td>{{ $report['week3']->l3 }}</td>
                    <td>{{ $report['week4']->l3 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->l3 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->l3 }}</td>
                    @endif
                    <td>{{ $report['total']->l3 }}</td>
                    <td>{{ $report['rangeDate']->l3 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L6</td>
                    <td>{{ $report['week1']->l6 }}</td>
                    <td>{{ $report['week2']->l6 }}</td>
                    <td>{{ $report['week3']->l6 }}</td>
                    <td>{{ $report['week4']->l6 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->l6 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->l6 }}</td>
                    @endif
                    <td>{{ $report['total']->l6 }}</td>
                    <td>{{ $report['rangeDate']->l6 }}</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L8</td>
                    <td>{{ $report['week1']->l8 }}</td>
                    <td>{{ $report['week2']->l8 }}</td>
                    <td>{{ $report['week3']->l8 }}</td>
                    <td>{{ $report['week4']->l8 }}</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ $report['week5']->l8 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ $report['week6']->l8 }}</td>
                    @endif
                    <td>{{ $report['total']->l8 }}</td>
                    <td>{{ $report['rangeDate']->l8 }}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: #2ea8e5; font-size: medium">QUALITY</td>
                </tr>
                <tr style="font-weight: bold; font-size: medium">
                    <td style="background-color: #ba871f">Actual</td>
                    <td>L3/C3B %</td>
                    <td>{{ ($report['week1']->c3b != 0) ? round($report['week1']->l3 / $report['week1']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->c3b != 0) ? round($report['week2']->l3 / $report['week2']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->c3b != 0) ? round($report['week3']->l3 / $report['week3']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->c3b != 0) ? round($report['week4']->l3 / $report['week4']->c3b,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3b != 0) ? round($report['week5']->l3 / $report['week5']->c3b,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->c3b != 0) ? round($report['week6']->l3 / $report['week6']->c3b,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->c3b != 0) ? round($report['total']->l3 / $report['total']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->c3b,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td style="text-align: center">L3/C3BG %</td>
                    <td>{{ ($report['week1']->c3bg != 0) ? round($report['week1']->l3 / $report['week1']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->c3bg != 0) ? round($report['week2']->l3 / $report['week2']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->c3bg != 0) ? round($report['week3']->l3 / $report['week3']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->c3bg != 0) ? round($report['week4']->l3 / $report['week4']->c3bg,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3bg != 0) ? round($report['week5']->l3 / $report['week5']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->c3bg != 0) ? round($report['week6']->l3 / $report['week6']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->c3bg != 0) ? round($report['total']->l3 / $report['total']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->c3bg,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td style="text-align: center">L3/L1 %</td>
                    <td>{{ ($report['week1']->l1 != 0) ? round($report['week1']->l3 / $report['week1']->l1,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->l1 != 0) ? round($report['week2']->l3 / $report['week2']->l1,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->l1 != 0) ? round($report['week3']->l3 / $report['week3']->l1,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->l1 != 0) ? round($report['week4']->l3 / $report['week4']->l1,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l1 != 0) ? round($report['week5']->l3 / $report['week5']->l1,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->l1 != 0) ? round($report['week6']->l3 / $report['week6']->l1,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->l1 != 0) ? round($report['total']->l3 / $report['total']->l1,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->l1 != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->l1,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td style="text-align: center">L1/C3BG %</td>
                    <td>{{ ($report['week1']->c3bg != 0) ? round($report['week1']->l1 / $report['week1']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->c3bg != 0) ? round($report['week2']->l1 / $report['week2']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->c3bg != 0) ? round($report['week3']->l1 / $report['week3']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->c3bg != 0) ? round($report['week4']->l1 / $report['week4']->c3bg,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3bg != 0) ? round($report['week5']->l1 / $report['week5']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->c3bg != 0) ? round($report['week6']->l1 / $report['week6']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->c3bg != 0) ? round($report['total']->l1 / $report['total']->c3bg,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->l1 / $report['rangeDate']->c3bg,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-style: italic;">
                    <td></td>
                    <td style="text-align: center">C3BG/C3B %</td>
                    <td>{{ ($report['week1']->c3b != 0) ? round($report['week1']->c3bg / $report['week1']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->c3b != 0) ? round($report['week2']->c3bg / $report['week2']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->c3b != 0) ? round($report['week3']->c3bg / $report['week3']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->c3b != 0) ? round($report['week4']->c3bg / $report['week4']->c3b,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->c3b != 0) ? round($report['week5']->c3bg / $report['week5']->c3b,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->c3b != 0) ? round($report['week6']->c3bg / $report['week6']->c3b,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->c3b != 0) ? round($report['total']->c3bg / $report['total']->c3b,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->c3bg / $report['rangeDate']->c3b,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>Return Ratio</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>Duplicate Ratio</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L6/L3 %</td>
                    <td>{{ ($report['week1']->l3 != 0) ? round($report['week1']->l6 / $report['week1']->l3,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->l3 != 0) ? round($report['week2']->l6 / $report['week2']->l3,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->l3 != 0) ? round($report['week3']->l6 / $report['week3']->l3,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->l3 != 0) ? round($report['week4']->l6 / $report['week4']->l3,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l3 != 0) ? round($report['week5']->l6 / $report['week5']->l3,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->l3 != 0) ? round($report['week6']->l6 / $report['week6']->l3,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->l3 != 0) ? round($report['total']->l6 / $report['total']->l3,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->l3 != 0) ? round($report['rangeDate']->l6 / $report['rangeDate']->l3,4)*100 : 0 }}%</td>
                    <td>0</td>
                </tr>
                <tr style="font-weight: bold">
                    <td></td>
                    <td>L8/L6 %</td>
                    <td>{{ ($report['week1']->l6 != 0) ? round($report['week1']->l8 / $report['week1']->l6,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week2']->l6 != 0) ? round($report['week2']->l8 / $report['week2']->l6,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week3']->l6 != 0) ? round($report['week3']->l8 / $report['week3']->l6,4)*100 : 0 }}%</td>
                    <td>{{ ($report['week4']->l6 != 0) ? round($report['week4']->l8 / $report['week4']->l6,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td>{{ ($report['week5']->l6 != 0) ? round($report['week5']->l8 / $report['week5']->l6,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td>{{ ($report['week6']->l6 != 0) ? round($report['week6']->l8 / $report['week6']->l6,4)*100 : 0 }}%</td>
                    @endif
                    <td>{{ ($report['total']->l6 != 0) ? round($report['total']->l8 / $report['total']->l6,4)*100 : 0 }}%</td>
                    <td>{{ ($report['rangeDate']->l6 != 0) ? round($report['rangeDate']->l8 / $report['rangeDate']->l6,4)*100 : 0 }}%</td>
                    <td>0</td>
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
</style>
