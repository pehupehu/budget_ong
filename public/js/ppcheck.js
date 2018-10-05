
let check_uncheck_all = $('.table-listing .pp-check-uncheck-all')
    , check_uncheck_id = $('.table-listing .pp-check-uncheck-id')
    , check_uncheck_action = $('.pp-check-uncheck-action');

check_uncheck_all.on('click', function() {
    let toogle = $(this).prop('checked');
    check_uncheck_id.prop('checked', toogle);
    check_uncheck_id.trigger('change');
});

check_uncheck_id.on('change', function() {
    let row = $(this).parents('.pp-check-uncheck-row');
    
    if ($(this).prop('checked')) {
        row.addClass('bg-light');
    } else {
        row.removeClass('bg-light');
    }
});

check_uncheck_action.on('click', function() {
    let uri = $(this).data('target');  
    let ids = [];
    check_uncheck_id.each(function () {
        if ($(this).prop('checked')) {
            ids.push($(this).data('id'));
        }
    });
    if (ids.length && uri !== undefined) {
        window.location.href = uri + '?ids=' + JSON.stringify(ids);
    }
})