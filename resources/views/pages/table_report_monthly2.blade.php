<div class="widget-body no-padding">
    <?php $totalC1 = 0 ?>
    <?php $totalC2 = 0 ?>
    <?php $totalC3 = 0 ?>
    <?php $totalC3B = 0 ?>
    <?php $totalC3BG = 0 ?>
    <?php $totalSpent = 0 ?>
    <?php $totalL1 = 0 ?>
    <?php $totalL3 = 0 ?>
    <?php $totalL6 = 0 ?>
    <?php $totalL8 = 0 ?>
    <?php $totalRevenue = 0 ?>
    @foreach($report as $key => $value)
        <?php $totalC1 += $report[$key]->c1 ?>
        <?php $totalC2 += $report[$key]->c2 ?>
        <?php $totalC3 += $report[$key]->c3 ?>
        <?php $totalC3B += $report[$key]->c3b ?>
        <?php $totalC3BG += $report[$key]->c3bg ?>
        <?php $totalSpent += $report[$key]->spent ?>
        <?php $totalL1 += $report[$key]->l1 ?>
        <?php $totalL3 += $report[$key]->l3 ?>
        <?php $totalL6 += $report[$key]->l6 ?>
        <?php $totalL8 += $report[$key]->l8 ?>
        <?php $totalRevenue += $report[$key]->revenue ?>
    @endforeach
    <table class="table" width="100%">
        <tr>
            <h1 style="text-align: center; font-weight: bold">MARKETING_INDEX REPORT</h1>
        </tr>
        <tr>
            <td><h3>Budget :</h3></td>
            <td></td>
            <td><h3>Target L1 :</h3></td>
            <td></td>
            <td><h3>L3/C3B :</h3></td>
            <td></td>
        </tr>
        <tr>
            <td><h4>Spent :</h4></td>
            <td><h4>{{ $totalSpent }} USD</h4></td>
            <td><h4>Produced (L1):</h4></td>
            <td><h4>{{ $totalL1 }} </h4></td>
            <td><h4>Actual (L3/C3B) : </h4></td>
            <td><h4>{{ ($totalC3B != 0) ? round($totalL3 / $totalC3B,4) * 100 : 0 }}%</h4></td>
        </tr>
    </table>
        <div class="wrapper_report">
            <table id="table_report" class="table" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Week 1</th>
                        <th>Week 2</th>
                        <th>Week 3</th>
                        <th>Week 4</th>
                        <th>Week 5</th>
                        <th>Total</th>
                        <th>Filter Range</th>
                        <th>Forecast</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th id="week1">
                            <input type="text" name="from" value="01" style='width:20px'/>
                            <input type="text" name="to" value="07" style='width:20px'/>
                        </th>
                        <th id="week2">
                            <input type="text" name="from" value="08" style='width:20px'/>
                            <input type="text" name="to" value="14" style='width:20px'/>
                        </th>
                        <th id="week3">
                            <input type="text" name="from" value="15" style='width:20px'/>
                            <input type="text" name="to" value="21" style='width:20px'/>
                        </th>
                        <th id="week4">
                            <input type="text" name="from" value="22" style='width:20px'/>
                            <input type="text" name="to" value="28" style='width:20px'/>
                        </th>
                        <th id="week5">
                            <input type="text" name="from" value="29" style='width:20px'/>
                            <input type="text" name="to" value="31" style='width:20px'/>
                        </th>
                        <th id="total">
                            <input type="text" name="from" value="01" style='width:20px'/>
                            <input type="text" name="to" value="31" style='width:20px'/>
                        </th>
                        <th id="filter">
                            <input type="text" name="from" value="1" style='width:20px'/>
                            <input type="text" name="to" value="31" style='width:20px'/>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
</div>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

<script type="text/javascript">
    $(document).ready(function () {
        var start = moment();
        var end = moment();

        function rangetime_span(start, end) {
            $('#rangetime span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
        }

        rangetime_span(start, end);

        $('#rangetime').daterangepicker({
            startDate: start,
            endDate: end,
            opens: 'right',
        }, rangetime_span);
    });

</script>