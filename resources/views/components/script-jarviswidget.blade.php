<script type="text/javascript">
    var __arr_month = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    var __arr_last_month = ["Last 6 months", "Last 12 months", "Last 18 months", "Last 24 months"];
    $( document ).ready(function() {
        var d = new Date();
        var current_month = d.getMonth();
        var dropdown = $('button#dropdown');
        dropdown.html(__arr_month[current_month]);
        $('h2#c3_chart').html('C3 in ' + dropdown.html());
        $('h2#l8_chart').html('L8 in ' + dropdown.html());
        $('h2#monthly_chart').html('Report month <span class="yellow">' + dropdown.html()+ '</span>');

        var dropdownY = $('button#dropdownY');
        dropdownY.html(__arr_last_month[1]);
        document.getElementById('dropdownY').parentElement.parentElement.parentElement.classList.add('sticky');
        document.getElementById('dropdown').parentElement.parentElement.parentElement.classList.add('sticky');
    });

    $('li#month').click(function() {
        var month       = $(this).val();
        var dropdown    = $(this).closest('ul').siblings();
        dropdown.html(__arr_month[month - 1]);

        var title       = $(this).parents('div.widget-toolbar').siblings('h2');
        var title_id    = title.attr('id');
        if(title_id == 'c3_chart'){
            title.html('C3 in ' + dropdown.html());
            get_c3_chart(month);
        } else if (title_id == 'l8_chart'){
            title.html('L8 in ' + dropdown.html());
            get_l8_chart(month);
        } else if (title_id == 'monthly_chart'){
            title.html('Report month <span class="yellow">' + dropdown.html()+ '</span>');
            get_report_monthly(month, new Date(), new Date());
        }
    });

    $('li#lastMonth').click(function() {
        var noMonth = $(this).val();
        var dropdownY = $(this).closest('ul').siblings();
        dropdownY.html(__arr_last_month[(noMonth/6) - 1]);

        var title    = $(this).parents('div.widget-toolbar').siblings('h2');
        var title_id = title.attr('id');
        var m = new Date().getMonth() + 1;
        var y = new Date().getFullYear();
        if (title_id == 'year_chart') {
            var r = noMonth - m;
            console.log(r);
            if (r > 12) {
                title.html('Report year <span class="yellow">' + (y - 2) + ' - ' + y + '</span>');
            } else if (r > 0) {
                title.html('Report year <span class="yellow">' + (y - 1) + ' - ' + y + '</span>');
            } else {
                title.html('Report year <span class="yellow">' + y + '</span>');
            }
            get_report_year(y, m, noMonth);
        }
    });

</script>