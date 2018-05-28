<div class="widget-body no-padding">
    <?php $usd_vnd = $report['config']['USD_VND'] ?>
    <?php $usd_thb = $report['config']['USD_THB'] ?>
        <div style="font-size: xx-large; border: #303030 !important ; padding: 15px 0;" class="bold center blue bg-gray1">MARKETING_INDEX REPORT</div>
        <div style="font-size: x-large; border-top: 1px solid #ffffff !important; padding: 15px 0; float: left; width: 100%" class="bold italic blue bg-gray2">
            <div class="inlineBlock col-md-2">Budget :</div><!--
        --><div class="orange inlineBlock col-md-2"></div><!--
        --><div class="inlineBlock col-md-3">Target L1 :</div><!--
        --><div class="orange inlineBlock col-md-1"></div><!--
        --><div class="inlineBlock col-md-3">L3/C3B :</div><!--
        --><div class="orange inlineBlock col-md-1"></div>
        </div>
        <div style="font-size: x-large; padding: 15px 0; float: left; width: 100%;" class="bold blue bg-gray2">
            <div class="inlineBlock col-md-2">Spent :</div><!--
        --><div class="orange inlineBlock col-md-2">{{ $report['total']->spent }} USD</div><!--
        --><div class="inlineBlock col-md-3">Produced (L1) :</div><!--
        --><div class="orange inlineBlock col-md-1">{{ $report['total']->l1 }} </div><!--
        --><div class="inlineBlock col-md-3">Actual (L3/C3BG) : </div><!--
        --><div class="orange inlineBlock col-md-1">{{ ($report['total']->c3bg != 0) ? round($report['total']->l3 / $report['total']->c3bg,4)*100 : 0 }}%</div>
        </div>
    <div class="wrapper_report_monthly gray">
        <table id="table_report" class="table" width="100%">
            <thead>
                <tr class="font-medium orange" style="background: #383838">
                    <th style="border-top: 2px solid #ffffff;"></th>
                    <th style="border-top: 2px solid #ffffff;"></th>
                    <th class="center" style="border: 2px solid #ffffff;">Week 1</th>
                    <th class="center" style="border: 2px solid #ffffff;">Week 2</th>
                    <th class="center" style="border: 2px solid #ffffff;">Week 3</th>
                    <th class="center" style="border: 2px solid #ffffff;">Week 4</th>
                    @if($report['week5']->range != NULL)
                    <th class="center" style="border: 2px solid #ffffff;">Week 5</th>
                    @endif
                    @if($report['week6']->range != NULL)
                    <th class="center" style="border: 2px solid #ffffff;">Week 6</th>
                    @endif
                    <th class="center" style="border: 2px solid #ffffff;">Total</th>
                    <th class="center" style="border: 2px solid #ffffff;">Range Date</th>
                    <th class="center" style="border-top: 2px solid #ffffff; border-left: 2px solid #ffffff;">Forecast</th>
                </tr>
                <tr class="italic center orange" style="background: #484848;">
                    <th></th>
                    <th style="border-right: 2px solid #ffffff;"></th>
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week1']->range }}</th>
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week2']->range }}</th>
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week3']->range }}</th>
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week4']->range }}</th>
                    @if($report['week5']->range != NULL)
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week5']->range }}</th>
                    @endif
                    @if($report['week6']->range != NULL)
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['week6']->range }}</th>
                    @endif
                    <th class="center" style="border-right: 2px solid #ffffff;">{{ $report['total']->range }}</th>
                    <th style="border-right: 2px solid #ffffff;">
                        <div id="rangedate" class="pull-left"
                             style="background: #fff; cursor: pointer; padding: 5px; border: 1px solid #ccc; margin: auto">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span class="rangedate"></span> <b class="caret"></b>
                        </div>
                    </th>
                    <th style="border-right: 2px solid #ffffff;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold font-medium bg-blue white">BUDGET</td>
                    <td class="bg-gray3"></td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange bg-gray2">Actual</td>
                    <td class="bg-gray2">ME/RE %</td>
                    <td class="center">{{ ($report['week1']->revenue != 0) ? round($report['week1']->spent * $usd_thb / $report['week1']->revenue,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->revenue != 0) ? round($report['week2']->spent * $usd_thb / $report['week2']->revenue,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->revenue != 0) ? round($report['week3']->spent * $usd_thb / $report['week3']->revenue,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->revenue != 0) ? round($report['week4']->spent * $usd_thb / $report['week4']->revenue,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->revenue != 0) ? round($report['week5']->spent * $usd_thb / $report['week5']->revenue,4)*100 : 0 }}%</td>
                    @endif
                    @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->revenue != 0) ? round($report['week6']->spent * $usd_thb / $report['week6']->revenue,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->revenue != 0) ? round($report['total']->spent * $usd_thb / $report['total']->revenue,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->revenue != 0) ? round($report['rangeDate']->spent * $usd_thb / $report['rangeDate']->revenue,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 yellow">ME (USD)</td>
                    <td class="center">{{ $report['week1']->spent }}</td>
                    <td class="center">{{ $report['week2']->spent }}</td>
                    <td class="center">{{ $report['week3']->spent }}</td>
                    <td class="center">{{ $report['week4']->spent }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->spent }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ $report['week6']->spent }}</td>
                    @endif
                    <td class="center">{{ $report['total']->spent }}</td>
                    <td class="center">{{ $report['rangeDate']->spent }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 yellow">RE (THB)</td>
                    <td class="center">{{ $report['week1']->revenue }}</td>
                    <td class="center">{{ $report['week2']->revenue }}</td>
                    <td class="center">{{ $report['week3']->revenue }}</td>
                    <td class="center">{{ $report['week4']->revenue }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->revenue }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ $report['week6']->revenue }}</td>
                    @endif
                    <td class="center">{{ $report['total']->revenue }}</td>
                    <td class="center">{{ $report['rangeDate']->revenue }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">C3B</td>
                    <td class="center">{{ ($report['week1']->c3b != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->c3b) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->c3b != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->c3b) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->c3b != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->c3b) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->c3b != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->c3b) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3b != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->c3b) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->c3b != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->c3b) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3b != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->c3b) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->c3b) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">C3BG</td>
                    <td class="center">{{ ($report['week1']->c3bg != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->c3bg) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->c3bg != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->c3bg) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->c3bg != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->c3bg) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->c3bg != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->c3bg) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3bg != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->c3bg) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->c3bg != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->c3bg) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3bg != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->c3bg) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->c3bg) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">L1 used</td>
                    <td class="center">{{ ($report['week1']->l1 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l1) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->l1 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l1) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->l1 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l1) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->l1 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l1) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l1 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l1) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->l1 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l1) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->l1 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l1) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->l1 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l1) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">L3</td>
                    <td class="center">{{ ($report['week1']->l3 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l3) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->l3 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l3) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->l3 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l3) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->l3 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l3) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l3 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l3) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->l3 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l3) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->l3 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l3) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->l3 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l3) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">L6</td>
                    <td class="center">{{ ($report['week1']->l6 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l6) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->l6 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l6) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->l6 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l6) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->l6 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l6) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l6 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l6) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->l6 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l6) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->l6 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l6) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->l6 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l6) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 white">L8</td>
                    <td class="center">{{ ($report['week1']->l8 != 0) ? round($report['week1']->spent * $usd_vnd / $report['week1']->l8) : 0 }}</td>
                    <td class="center">{{ ($report['week2']->l8 != 0) ? round($report['week2']->spent * $usd_vnd / $report['week2']->l8) : 0 }}</td>
                    <td class="center">{{ ($report['week3']->l8 != 0) ? round($report['week3']->spent * $usd_vnd / $report['week3']->l8) : 0 }}</td>
                    <td class="center">{{ ($report['week4']->l8 != 0) ? round($report['week4']->spent * $usd_vnd / $report['week4']->l8) : 0 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l8 != 0) ? round($report['week5']->spent * $usd_vnd / $report['week5']->l8) : 0 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                    <td class="center">{{ ($report['week6']->l8 != 0) ? round($report['week6']->spent * $usd_vnd / $report['week6']->l8) : 0 }}</td>
                    @endif
                    <td class="center">{{ ($report['total']->l8 != 0) ? round($report['total']->spent * $usd_vnd / $report['total']->l8) : 0 }}</td>
                    <td class="center">{{ ($report['rangeDate']->l8 != 0) ? round($report['rangeDate']->spent * $usd_vnd / $report['rangeDate']->l8) : 0 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr>
                    <td class="bold font-medium bg-blue white">QUANTITY</td>
                    <td class="bg-gray3"></td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange bg-gray2">Actual</td>
                    <td class="bg-gray2">C3B</td>
                    <td class="center">{{ $report['week1']->c3b }}</td>
                    <td class="center">{{ $report['week2']->c3b }}</td>
                    <td class="center">{{ $report['week3']->c3b }}</td>
                    <td class="center">{{ $report['week4']->c3b }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->c3b }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->c3b }}</td>
                    @endif
                    <td class="center">{{ $report['total']->c3b }}</td>
                    <td class="center">{{ $report['rangeDate']->c3b }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">C3BG</td>
                    <td class="center">{{ $report['week1']->c3bg }}</td>
                    <td class="center">{{ $report['week2']->c3bg }}</td>
                    <td class="center">{{ $report['week3']->c3bg }}</td>
                    <td class="center">{{ $report['week4']->c3bg }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->c3bg }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->c3bg }}</td>
                    @endif
                    <td class="center">{{ $report['total']->c3bg }}</td>
                    <td class="center">{{ $report['rangeDate']->c3bg }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L1 used</td>
                    <td class="center">{{ $report['week1']->l1 }}</td>
                    <td class="center">{{ $report['week2']->l1 }}</td>
                    <td class="center">{{ $report['week3']->l1 }}</td>
                    <td class="center">{{ $report['week4']->l1 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->l1 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->l1 }}</td>
                    @endif
                    <td class="center">{{ $report['total']->l1 }}</td>
                    <td class="center">{{ $report['rangeDate']->l1 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L3</td>
                    <td class="center">{{ $report['week1']->l3 }}</td>
                    <td class="center">{{ $report['week2']->l3 }}</td>
                    <td class="center">{{ $report['week3']->l3 }}</td>
                    <td class="center">{{ $report['week4']->l3 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->l3 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->l3 }}</td>
                    @endif
                    <td class="center">{{ $report['total']->l3 }}</td>
                    <td class="center">{{ $report['rangeDate']->l3 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L6</td>
                    <td class="center">{{ $report['week1']->l6 }}</td>
                    <td class="center">{{ $report['week2']->l6 }}</td>
                    <td class="center">{{ $report['week3']->l6 }}</td>
                    <td class="center">{{ $report['week4']->l6 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->l6 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->l6 }}</td>
                    @endif
                    <td class="center">{{ $report['total']->l6 }}</td>
                    <td class="center">{{ $report['rangeDate']->l6 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L8</td>
                    <td class="center">{{ $report['week1']->l8 }}</td>
                    <td class="center">{{ $report['week2']->l8 }}</td>
                    <td class="center">{{ $report['week3']->l8 }}</td>
                    <td class="center">{{ $report['week4']->l8 }}</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ $report['week5']->l8 }}</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ $report['week6']->l8 }}</td>
                    @endif
                    <td class="center">{{ $report['total']->l8 }}</td>
                    <td class="center">{{ $report['rangeDate']->l8 }}</td>
                    <td class="center">0</td>
                </tr>
                <tr>
                    <td class="bold font-medium bg-blue white">QUALITY</td>
                    <td class="bg-gray3"></td>
                </tr>
                <tr class="bold font-medium blue">
                    <td class="orange bg-gray2">Actual</td>
                    <td class="bg-gray2">L3/C3B %</td>
                    <td class="center">{{ ($report['week1']->c3b != 0) ? round($report['week1']->l3 / $report['week1']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->c3b != 0) ? round($report['week2']->l3 / $report['week2']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->c3b != 0) ? round($report['week3']->l3 / $report['week3']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->c3b != 0) ? round($report['week4']->l3 / $report['week4']->c3b,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3b != 0) ? round($report['week5']->l3 / $report['week5']->c3b,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->c3b != 0) ? round($report['week6']->l3 / $report['week6']->c3b,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3b != 0) ? round($report['total']->l3 / $report['total']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 yellow">L3/C3BG %</td>
                    <td class="center">{{ ($report['week1']->c3bg != 0) ? round($report['week1']->l3 / $report['week1']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->c3bg != 0) ? round($report['week2']->l3 / $report['week2']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->c3bg != 0) ? round($report['week3']->l3 / $report['week3']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->c3bg != 0) ? round($report['week4']->l3 / $report['week4']->c3bg,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3bg != 0) ? round($report['week5']->l3 / $report['week5']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->c3bg != 0) ? round($report['week6']->l3 / $report['week6']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3bg != 0) ? round($report['total']->l3 / $report['total']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 yellow">L3/L1 %</td>
                    <td class="center">{{ ($report['week1']->l1 != 0) ? round($report['week1']->l3 / $report['week1']->l1,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->l1 != 0) ? round($report['week2']->l3 / $report['week2']->l1,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->l1 != 0) ? round($report['week3']->l3 / $report['week3']->l1,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->l1 != 0) ? round($report['week4']->l3 / $report['week4']->l1,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l1 != 0) ? round($report['week5']->l3 / $report['week5']->l1,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->l1 != 0) ? round($report['week6']->l3 / $report['week6']->l1,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->l1 != 0) ? round($report['total']->l3 / $report['total']->l1,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->l1 != 0) ? round($report['rangeDate']->l3 / $report['rangeDate']->l1,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 yellow">L1/C3BG %</td>
                    <td class="center">{{ ($report['week1']->c3bg != 0) ? round($report['week1']->l1 / $report['week1']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->c3bg != 0) ? round($report['week2']->l1 / $report['week2']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->c3bg != 0) ? round($report['week3']->l1 / $report['week3']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->c3bg != 0) ? round($report['week4']->l1 / $report['week4']->c3bg,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3bg != 0) ? round($report['week5']->l1 / $report['week5']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->c3bg != 0) ? round($report['week6']->l1 / $report['week6']->c3bg,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3bg != 0) ? round($report['total']->l1 / $report['total']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->c3bg != 0) ? round($report['rangeDate']->l1 / $report['rangeDate']->c3bg,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="italic">
                    <td class="bg-gray2"></td>
                    <td class="center bg-gray2 yellow">C3BG/C3B %</td>
                    <td class="center">{{ ($report['week1']->c3b != 0) ? round($report['week1']->c3bg / $report['week1']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->c3b != 0) ? round($report['week2']->c3bg / $report['week2']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->c3b != 0) ? round($report['week3']->c3bg / $report['week3']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->c3b != 0) ? round($report['week4']->c3bg / $report['week4']->c3b,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->c3b != 0) ? round($report['week5']->c3bg / $report['week5']->c3b,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->c3b != 0) ? round($report['week6']->c3bg / $report['week6']->c3b,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->c3b != 0) ? round($report['total']->c3bg / $report['total']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->c3b != 0) ? round($report['rangeDate']->c3bg / $report['rangeDate']->c3b,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">Return Ratio</td>
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
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">Duplicate Ratio</td>
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
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L6/L3 %</td>
                    <td class="center">{{ ($report['week1']->l3 != 0) ? round($report['week1']->l6 / $report['week1']->l3,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->l3 != 0) ? round($report['week2']->l6 / $report['week2']->l3,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->l3 != 0) ? round($report['week3']->l6 / $report['week3']->l3,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->l3 != 0) ? round($report['week4']->l6 / $report['week4']->l3,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l3 != 0) ? round($report['week5']->l6 / $report['week5']->l3,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->l3 != 0) ? round($report['week6']->l6 / $report['week6']->l3,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->l3 != 0) ? round($report['total']->l6 / $report['total']->l3,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->l3 != 0) ? round($report['rangeDate']->l6 / $report['rangeDate']->l3,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
                </tr>
                <tr class="bold">
                    <td class="bg-gray2"></td>
                    <td class="bg-gray2 white">L8/L6 %</td>
                    <td class="center">{{ ($report['week1']->l6 != 0) ? round($report['week1']->l8 / $report['week1']->l6,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week2']->l6 != 0) ? round($report['week2']->l8 / $report['week2']->l6,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week3']->l6 != 0) ? round($report['week3']->l8 / $report['week3']->l6,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['week4']->l6 != 0) ? round($report['week4']->l8 / $report['week4']->l6,4)*100 : 0 }}%</td>
                    @if($report['week5']->range != NULL)
                    <td class="center">{{ ($report['week5']->l6 != 0) ? round($report['week5']->l8 / $report['week5']->l6,4)*100 : 0 }}%</td>
                    @endif
                        @if($report['week6']->range != NULL)
                            <td class="center">{{ ($report['week6']->l6 != 0) ? round($report['week6']->l8 / $report['week6']->l6,4)*100 : 0 }}%</td>
                    @endif
                    <td class="center">{{ ($report['total']->l6 != 0) ? round($report['total']->l8 / $report['total']->l6,4)*100 : 0 }}%</td>
                    <td class="center">{{ ($report['rangeDate']->l6 != 0) ? round($report['rangeDate']->l8 / $report['rangeDate']->l6,4)*100 : 0 }}%</td>
                    <td class="center">0</td>
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
    .bg-gray1 {
        background: #383838;
    }
    .bg-gray2 {
        background: #484848;
    }
    .bg-gray3 {
        background: #585858;
    }
    .font-medium {
        font-size: medium;
    }
    .inlineBlock {
        display: inline-block;
    }
    .table > tbody > tr > td {
        border-bottom: 1px solid #ddd;
    }
</style>