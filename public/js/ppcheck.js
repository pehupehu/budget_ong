
let check_uncheck_all = $('.table-listing .pp-check-uncheck-all')
    , check_uncheck_id = $('.table-listing .pp-check-uncheck-id');

check_uncheck_all.on('click', function() {
    let toogle = $(this).prop('checked');
    check_uncheck_id.prop('checked', toogle);
});