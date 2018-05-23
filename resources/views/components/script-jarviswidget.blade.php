<script type="text/javascript">
    var __arr_month = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    $( document ).ready(function() {
        var d = new Date();
        var current_month = d.getMonth();
        var dropdown = $('button#dropdown');
        dropdown.html(__arr_month[current_month]);
        $('h2#c3_chart').html('C3 in ' + dropdown.html());
        $('h2#l8_chart').html('L8 in ' + dropdown.html());
        $('h2#monthly_chart').html('Report month ' + dropdown.html());
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
            title.html('Report month ' + dropdown.html());
            get_report_monthly(month);
        }
    });

</script>