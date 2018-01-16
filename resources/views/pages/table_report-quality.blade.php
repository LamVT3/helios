<table id="table_ads" class="table "
       width="100%">
    <thead>
        <tr>
            <th>Source</th>
            <th>Team</th>
            <th>MKTer</th>
            <th>Campaign</th>
            <th>Subcampaign</th>
            <th>Ad</th>
            <th>C1</th>
            <th class="long">C1 Cost (VND)</th>
            <th>C2</th>
            <th class="long">C2 Cost (VND)</th>
            <th>C3</th>
            <th class="long">C3 Cost (VND)</th>
            <th>C3B</th>
            <th class="long">C3B Cost (VND)</th>
            <th>C3/C2 (%)</th>
            <th>L1</th>
            <th>L3</th>
            <th>L8</th>
            <th>L3/L1 (%)</th>
            <th>L8/L1 (%)</th>
            <th class="long">Spent (USD)</th>
            <th class="long">Revenue (USD)</th>
            <th>ME/RE (%)</th>
        </tr>
    </thead>
    <tbody>
        <tr id="total">
            <td>{{ $source }}</td>
            <td>{{ $team }}</td>
            <td>{{ $marketer }}</td>
            <td>{{ $campaign }}</td>
            <td>All</td>
            <td>All</td>
            <td>{{ number_format($total['c1']) }}</td>
            <td>{{ number_format($total['c1_cost'], 2) }}</td>
            <td>{{ number_format($total['c2']) }}</td>
            <td>{{ number_format($total['c2_cost'], 2) }}</td>
            <td>{{ number_format($total['c3']) }}</td>
            <td>{{ number_format($total['c3_cost'], 2) }}</td>
            <td>{{ number_format($total['c3b']) }}</td>
            <td>{{ number_format($total['c3b_cost'], 2) }}</td>
            <td>{{ $total['c3_c2'] }}</td>
            <td>{{ $total['l1'] }}</td>
            <td>{{ $total['l3'] }}</td>
            <td>{{ $total['l8'] }}</td>
            <td>{{ $total['l3_l1'] }}</td>
            <td>{{ $total['l8_l1'] }}</td>
            <td>{{ number_format($total['spent'], 2) }}</td>
            <td>{{ number_format($total['revenue']) }}</td>
            <td>{{ $total['me_re'] }}</td>
        </tr>
        @foreach ($ads as $item)

        <tr id="ad-{{ $item->id }}">
            <td>{{ $item->source_name }}</td>
            <td>{{ $item->team_name }}</td>
            <td>{{ $item->creator_name }}</td>
            <td>{{ $item->campaign_name }}</td>
            <td>{{ $item->subcampaign_name }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ isset($results[$item->id]) ? number_format($results[$item->id]->c1) : 0 }}</td>
            <td>{{ $results[$item->id]->c1_cost or 0 }}</td>
            <td>{{ $results[$item->id]->c2 or 0}}</td>
            <td>{{ $results[$item->id]->c2_cost or 0 }}</td>
            <td>{{ $results[$item->id]->c3 or 0 }}</td>
            <td>{{ $results[$item->id]->c3_cost or 0 }}</td>
            <td>{{ $results[$item->id]->c3 or 0 }}</td>
            <td>{{ $results[$item->id]->c3b_cost or 0 }}</td>
            <td>{{ isset($results[$item->id]) && $results[$item->id]->c2 ? round($results[$item->id]->c3 / $results[$item->id]->c2, 4) * 100 : 'n/a' }}</td>
            <td>{{ $results[$item->id]->l1 or 0 }}</td>
            <td>{{ $results[$item->id]->l3 or 0 }}</td>
            <td>{{ $results[$item->id]->l8 or 0 }}</td>
            <td>{{ isset($results[$item->id]) && $results[$item->id]->l1 ? round($results[$item->id]->l3 / $results[$item->id]->l1, 4) * 100 : 'n/a' }}</td>
            <td>{{ isset($results[$item->id]) && $results[$item->id]->l1 ? round($results[$item->id]->l8 / $results[$item->id]->l1, 4) * 100 : 'n/a' }}</td>
            <td>{{ $results[$item->id]->spent or 0 }}</td>
            <td>{{ $results[$item->id]->revenue or 0 }}</td>
            <td>{{ isset($results[$item->id]) && $results[$item->id]->revenue ? round($results[$item->id]->spent / $results[$item->id]->revenue, 4) * 100 : 'n/a' }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
<script src="{{ asset('js/reports/table_report.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
