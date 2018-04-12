
var ppbox_dialog_inc = 0;

class PPbox {
    constructor() {
    }

    static redirect(url) {
        window.location.href = url;
    }

    static confirm(title, text, theme, width, buttons1, buttons2, buttons3) {
        this.createDialog(title, text, theme, width, buttons1, buttons2, buttons3);
    }

    static alert(title, text, theme, width, buttons1, buttons2, buttons3) {
        this.createDialog(title, text, theme, width, buttons1, buttons2, buttons3);
    }

    static closeDialog() {
        $('#ppboxdialog' + ppbox_dialog_inc).dialog('close');
    }

    static createDialog(title, text, theme, width, buttons1, buttons2, buttons3) {
        ppbox_dialog_inc = ppbox_dialog_inc + 1;

        let nb_buttons = 0;
        let options = {
            classes: {
                "ui-dialog": ("ui-corner-all ui-dialog-" + theme + " ui-dialog-" + width),
                "ui-dialog-titlebar": ("ui-corner-all ui-dialog-titlebar-" + theme + " ui-dialog-titlebar-" + width),
            },
            buttons: {}
        };

        if (buttons1 !== undefined) {
            let button = {};

            button.text = buttons1.text;
            if (buttons1.redirect !== undefined) {
                button.click = function() {
                    PPbox.redirect(buttons1.redirect);
                };
            } else {
                button.click = function() {
                    PPbox.closeDialog();
                }
            }

            if (buttons1.class !== undefined) {
                button.class = buttons1.class;
            } else {
                button.class = 'btn btn-sm btn-outline-secondary';
            }

            options.buttons[nb_buttons] = button;
            nb_buttons = nb_buttons+1;
        }
        if (buttons2 !== undefined) {
            let button = {};

            button.text = buttons2.text;
            if (buttons2.redirect !== undefined) {
                button.click = function() {
                    PPbox.redirect(buttons2.redirect);
                };
            } else {
                button.click = function() {
                    PPbox.closeDialog();
                }
            }

            if (buttons2.class !== undefined) {
                button.class = buttons2.class;
            } else {
                button.class = 'btn btn-sm btn-outline-secondary';
            }

            options.buttons[nb_buttons] = button;
            nb_buttons = nb_buttons+1;
        }

        $(document.body).append('<div id="ppboxdialog' + ppbox_dialog_inc + '" title="' + title + '">' + text + '</div>');
        $('#ppboxdialog' + ppbox_dialog_inc).dialog(options);
    }
}