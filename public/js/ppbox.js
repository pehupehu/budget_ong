class PPbox {
    constructor() {
    }

    static redirect(url) {
        window.location.href = url;
    }

    static confirm(id, title, body, theme, width, button1, button2, button3) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }

        if (this.dialogs[id] === undefined) {
            this.createDialog(id, title, body, theme, width, button1, button2, button3);
        } else {
            this.openDialog(id);
        }
    }

    static alert(id, title, body, theme, width, button1, button2, button3) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }

        if (this.dialogs[id] === undefined) {
            this.createDialog(id, title, body, theme, width, button1, button2, button3);
        } else {
            this.openDialog(id);
        }
    }

    static openDialog(id) {
        $('#' + id).dialog('open');
    }

    static closeDialog(id) {
        $('#' + id).dialog('close');
    }

    static createDialog(id, title, body, theme, width, button1, button2, button3) {
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

        if (button1 !== undefined) {
            let button = {};

            button.text = button1.text;
            if (button1.redirect !== undefined) {
                button.click = function() {
                    PPbox.redirect(button1.redirect);
                };
            } else {
                button.click = function() {
                    PPbox.closeDialog(id);
                }
            }

            if (button1.class !== undefined) {
                button.class = button1.class;
            } else {
                button.class = 'btn btn-sm btn-dark';
            }

            options.buttons[nb_buttons] = button;
            nb_buttons = nb_buttons+1;
        }
        if (button2 !== undefined) {
            let button = {};

            button.text = button2.text;
            if (button2.redirect !== undefined) {
                button.click = function() {
                    PPbox.redirect(button2.redirect);
                };
            } else {
                button.click = function() {
                    PPbox.closeDialog(id);
                }
            }

            if (button2.class !== undefined) {
                button.class = button2.class;
            } else {
                button.class = 'btn btn-sm btn-outline-dark';
            }

            options.buttons[nb_buttons] = button;
            nb_buttons = nb_buttons+1;
        }

        $(document.body).append('<div id="' + id + '" title="' + title + '">' + body + '</div>');
        $('#' + id).dialog(options);
    }
}
