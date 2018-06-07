<div class="statistic">
    <div class="widget-body-toolbar bg-color-white smart-form">
        <div class="row" id="statistic_chk">
            <div class="col col-sm">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="c3b" checked onclick="initStatisticChart();">
                        <div class="rectangle"><hr class="statistic-top-1">No C3 produced/tháng</div>
                    </label>
                </div>
            </div>
            <div class="col col-sm">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="c3b_price" checked onclick="initStatisticChart();">
                        <div class="rectangle"><hr class="statistic-top-2">Price of C3B produced</div>
                    </label>
                </div>
            </div>
            <div class="col col-sm">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="l3_c3bg" checked onclick="initStatisticChart();">
                        <div class="rectangle"><hr class="statistic-top-3">L3/C3B transfered</div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div id="statistic_report_chart" class="chart has-legend"></div>
    <input type="hidden" name="statistic_chart_url" id="statistic_chart_url" value="{{route('ajax-setStatisticChart')}}">
    <input type="hidden" name="statistic_chart_year" id="statistic_chart_year">
    <input type="hidden" name="statistic_chart_month" id="statistic_chart_month">
    <input type="hidden" name="statistic_chart_noMonth" id="statistic_chart_noMonth">
</div>