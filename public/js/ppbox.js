class PPbox {
    constructor() {
    }

    static redirect(url) {
        window.location.href = url;
    }

    static confirm(id, title, text, theme, width, buttons1, buttons2, buttons3) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }

        if (this.dialogs[id] === undefined) {
            this.createDialog(id, title, text, theme, width, buttons1, buttons2, buttons3);
        } else {
            this.openDialog(id);
        }
    }

    static alert(id, title, text, theme, width, buttons1, buttons2, buttons3) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }

        if (this.dialogs[id] === undefined) {
            this.createDialog(id, title, text, theme, width, buttons1, buttons2, buttons3);
        } else {
            this.openDialog(id);
        }
    }

    static openDialog(id) {
        $('#ppboxdialog' + id).dialog('open');
    }

    static closeDialog(id) {
        $('#ppboxdialog' + id).dialog('close');
    }

    static createDialog(id, title, text, theme, width, buttons1, buttons2, buttons3) {
        if (this.dialogs === undefined) {
            this.dialogs = 0;
        }
        this.dialogs[id] = true;

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
                    PPbox.closeDialog(id);
                }
            }

            if (buttons1.class !== undefined) {
                button.class = buttons1.class;
            } else {
                button.class = 'btn btn-sm btn-dark';
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
                    PPbox.closeDialog(id);
                }
            }

            if (buttons2.class !== undefined) {
                button.class = buttons2.class;
            } else {
                button.class = 'btn btn-sm btn-outline-dark';
            }

            options.buttons[nb_buttons] = button;
            nb_buttons = nb_buttons+1;
        }

        $(document.body).append('<div id="' + id + '" title="' + title + '">' + text + '</div>');
        $('#' + id).dialog(options);
    }
}