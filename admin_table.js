$('#adminorders').Tabledit({
    url: 'db_updater.php',
    columns: {
        identifier: [2, 'order_id'],
        editable:[[3, 'order_name'], [4, 'order_price'], [5, 'order_quantity'], [6, 'order_date'], [7, 'order_type', '{"Select type": "Select type", "Buying": "Buying", "Selling": "Selling", "Renting": "Renting", "Loaning": "Loaning", "Other": "Other"}'], [8, 'order_status', '{"Select status": "Select status", "In progress": "In progress", "Completed": "Completed", "Cancelled": "Cancelled"}']]
    },
    onDraw: function() {
        $('#adminorders td:nth-child(7) input').each(function() {
            $(this).datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: true
            });
        });
        console.log('onDraw()');
    },
    onSuccess: function(data, textStatus, jqXHR) {
        if(data.action == 'delete'){
            $('#' + data.id).remove();
            $('#adminorders').DataTable().ajax.reload();
        }
        console.log('onSuccess(data, textStatus, jqXHR)');
        console.log(data);
        console.log(textStatus);
        console.log(jqXHR);
            location.reload();
    },
    onFail: function(jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
            location.reload();
    },
    onAlways: function() {
        console.log('onAlways()');
    },
    onAjax: function(action, serialize) {
        console.log('onAjax(action, serialize)');
        console.log(action);
        console.log(serialize);
    }
});