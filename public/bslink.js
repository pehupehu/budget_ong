
function bslinkInit() {
    let bslink = {};

    let dialog_inc = 0;

    let closeDialog = function() {
        $(this).dialog('close');
    };

    bslink.redirect = function(url) {
        window.location.href = url;
    };

    bslink.confirm = function(title, text, options = null) {
        bslink.createDialog(title, text, options);
    };

    bslink.alert = function(title, text, options) {
        if (options === undefined) {
            options = {};
            options.buttons = {};
            options.buttons['ok'] = closeDialog;
        } else {
            if (options.buttons === undefined) {
                options.buttons = {};
                options.buttons['ok'] = closeDialog;
            }
        }

        bslink.createDialog(title, text, options);
    };

    bslink.createDialog = function(title, text, options = null) {
        dialog_inc = dialog_inc + 1;
        $(document.body).append('<div id="bslinkdialog' + dialog_inc + '" title="' + title + '">' + text + '</div>');

        return $('#bslinkdialog' + dialog_inc).dialog(options);
    };

    return bslink;
}

let bslink = bslinkInit();

bslink.alert('pouet', 'test 1', { buttons: { 'revenir': function() { window.location.href='tata.php' }, 'revenir2': function() { window.location.href='tata2.php' } }});
bslink.alert('pouet', 'test 2');