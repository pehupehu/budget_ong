


function ppboxInit() {
    let ppbox = {};
    let dialog_inc = 0;

    // let closeDialog = function() {
    //     $(this).dialog('close');
    // };

    ppbox.redirect = function(url) {
        window.location.href = url;
    };

    ppbox.confirm = function(title, text, theme = 'dark', options) {
        ppbox.createDialog(title, text, theme = 'dark', options);
    };

    ppbox.alert = function(title, text, theme = 'dark', options) {
        ppbox.createDialog(title, text, theme = 'dark', options);
    };

    ppbox.createDialog = function(title, text, theme = 'dark', options) {
        dialog_inc = dialog_inc + 1;
        $(document.body).append('<div id="ppboxdialog' + dialog_inc + '" title="' + title + '">' + text + '</div>');

        // Bootstrap dialog
        if (options === undefined) {
            options = {};
        }

        console.log(theme);

        if (options.classes === undefined) {
            options.classes = {
                "ui-dialog": ("ui-corner-all ui-dialog-" + theme),
                "ui-dialog-titlebar": ("ui-corner-all ui-dialog-titlebar-" + theme),
            }
        }

        // Bootstrap buttons
        // 'class': 'btn btn-sm btn-info',


        return $('#ppboxdialog' + dialog_inc).dialog(options);
    };

    return ppbox;
}

let ppbox = ppboxInit();

// ppbox.alert('pouet', 'test 1', { buttons: { 'revenir': function() { window.location.href='tata.php' }, 'revenir2': function() { window.location.href='tata2.php' } }});
ppbox.alert('pouet', 'test 2', 'dark', { buttons: { 'revenir': function() { $(this).dialog('close') } } });

// ppbox.confirm('pouet', 'test 1', { buttons: { 'revenir': function() { $(this).dialog('close') } }});
// ppbox.confirm('Confirm', 'Are you sure ?', {});