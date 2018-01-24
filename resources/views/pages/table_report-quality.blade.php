<table id="table_report" class="table "
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

        @foreach ($report as $id => $item)

            <tr id="ad-{{ $id }}">
                <td>{{ $item->source }}</td>
                <td>{{ $item->team }}</td>
                <td>{{ $item->marketer }}</td>
                <td>{{ $item->campaign }}</td>
                <td>{{ $item->subcampaign }}</td>
                <td>{{ $item->ad }}</td>
                <td>{{ number_format($item->c1) }}</td>
                <td>{{ number_format($item->c1_cost, 2) }}</td>
                <td>{{ number_format($item->c2) }}</td>
                <td>{{ number_format($item->c2_cost, 2) }}</td>
                <td>{{ number_format($item->c3) }}</td>
                <td>{{ number_format($item->c3_cost, 2) }}</td>
                <td>{{ number_format($item->c3b) }}</td>
                <td>{{ number_format($item->c3b_cost, 2) }}</td>
                <td>{{ $item->c3_c2 }}</td>
                <td>{{ $item->l1 }}</td>
                <td>{{ $item->l3 }}</td>
                <td>{{ $item->l8 }}</td>
                <td>{{ $item->l3_l1 }}</td>
                <td>{{ $item->l8_l1 }}</td>
                <td>{{ number_format($item->spent, 2) }}</td>
                <td>{{ number_format($item->revenue) }}</td>
                <td>{{ $item->me_re }}</td>
            </tr>

        @endforeach

    </tbody>
</table>

