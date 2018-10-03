
let check_uncheck_all = $('.table-listing .pp-check-uncheck-all')
    , check_uncheck_id = $('.table-listing .pp-check-uncheck-id');

check_uncheck_all.on('click', function() {
    let toogle = $(this).prop('checked');
    check_uncheck_id.prop('checked', toogle);
    check_uncheck_id.trigger('change');
});

let check_uncheck_ids = $('.pp-check-uncheck-id');

check_uncheck_ids.on('change', function() {
    let row = $(this).parents('.pp-check-uncheck-row');
    
    if ($(this).prop('checked')) {
        row.addClass('bg-light');
    } else {
        row.removeClass('bg-light');
    }
});