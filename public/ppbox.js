


function ppboxInit() {
    let ppbox = {};
    let dialog_inc = 0;

    // let closeDialog = function() {
    //     $(this).dialog('close');
    // };

    ppbox.redirect = function(url) {
        window.location.href = url;
    };

    ppbox.confirm = function(title, text, options = null) {
        ppbox.createDialog(title, text, options);
    };

    ppbox.alert = function(title, text, options) {
        ppbox.createDialog(title, text, options);
    };

    ppbox.createDialog = function(title, text, options = null) {
        dialog_inc = dialog_inc + 1;
        $(document.body).append('<div id="ppboxdialog' + dialog_inc + '" title="' + title + '">' + text + '</div>');

        // Bootstrap dialog

        // 'classes': {
        //     "ui-dialog": "ui-corner-allaaaaaaaa",
        //         "ui-dialog-titlebar": "ui-corner-allaaaaaaaaaaaaabbbbbb",
        // }


        // Bootstrap buttons
        // 'class': 'btn btn-sm btn-info',


        return $('#ppboxdialog' + dialog_inc).dialog(options);
    };

    return ppbox;
}

let ppbox = ppboxInit();

// ppbox.alert('pouet', 'test 1', { buttons: { 'revenir': function() { window.location.href='tata.php' }, 'revenir2': function() { window.location.href='tata2.php' } }});
// ppbox.alert('pouet', 'test 2', { buttons: { 'revenir': function() { $(this).dialog('close') } } });

// ppbox.confirm('pouet', 'test 1', { buttons: { 'revenir': function() { $(this).dialog('close') } }});
// ppbox.confirm('Confirm', 'Are you sure ?', {});