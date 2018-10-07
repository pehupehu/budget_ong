let ppboxconfirm = $('.ppboxconfirm'),
    ppboxalert = $('.ppboxalert');

ppboxconfirm.on('click', function () {
    let id = $(this).data('ppbox-id'),
        title = $(this).data('ppbox-title'),
        body = $(this).data('ppbox-body'),
        theme = $(this).data('ppbox-theme'),
        width = $(this).data('ppbox-width'),
        button1 = $(this).data('ppbox-button1'),
        button2 = $(this).data('ppbox-button2'),
        button3 = $(this).data('ppbox-button3');

    PPbox.confirm(id, title, body, theme, width, button1, button2, button3)
});

ppboxalert.on('click', function () {
    let id = $(this).data('ppbox-id'),
        title = $(this).data('ppbox-title'),
        body = $(this).data('ppbox-body'),
        theme = $(this).data('ppbox-theme'),
        width = $(this).data('ppbox-width'),
        button1 = $(this).data('ppbox-button1'),
        button2 = $(this).data('ppbox-button2'),
        button3 = $(this).data('ppbox-button3');

    PPbox.alert(id, title, body, theme, width, button1, button2, button3)
});
