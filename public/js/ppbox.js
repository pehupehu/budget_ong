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
            this._createDialog(id, title, body, theme, width, button1, button2, button3);
        } else {
            this._openDialog(id, title, body, theme, width, button1, button2, button3);
        }
    }

    static alert(id, title, body, theme, width, button1, button2, button3) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }

        if (this.dialogs[id] === undefined) {
            this._createDialog(id, title, body, theme, width, button1, button2, button3);
        } else {
            this._openDialog(id, title, body, theme, width, button1, button2, button3);
        }
    }

    static _openDialog(id, title, body, theme, width, button1, button2, button3) {
        let d = $('#' + id);
        d.data('title', title);
        d.html(body);
        d.dialog(this._buildOptions(id, theme, width, button1, button2, button3));
        d.dialog('open');
    }

    static _closeDialog(id) {
        $('#' + id).dialog('close');
    }

    static _createDialog(id, title, body, theme, width, button1, button2, button3) {
        if (this.dialogs === undefined) {
            this.dialogs = 0;
        }
        this.dialogs[id] = true;

        $(document.body).append('<div id="' + id + '" title="' + title + '">' + body + '</div>');
        $('#' + id).dialog(this._buildOptions(id, theme, width, button1, button2, button3));
    }

    static _buildOptions(id, theme, width, button1, button2, button3) {
        let options = {
            classes: {
                "ui-dialog": ("ui-corner-all ui-dialog-" + theme + " ui-dialog-" + width),
                "ui-dialog-titlebar": ("ui-corner-all ui-dialog-titlebar-" + theme + " ui-dialog-titlebar-" + width),
            },
            buttons: {}
        };

        options.buttons = this._buildButtons(id, button1, button2, button3);

        return options;
    }

    static _buildButton(id, button, params) {
        if (button.route !== undefined) {
            if (button.route_params === undefined) {
                button.route_params = {};
            }

            if (params !== undefined) {
                for (var key in params) {
                    button.route_params[key] = params[key];
                }
            }

            button.click = function () {
                let url = Routing.generate(button.route, button.route_params);
                PPbox.redirect(url);
            };
        } else if (button.redirect !== undefined) {
            button.click = function () {
                PPbox.redirect(button.redirect);
            };
        } else {
            button.click = function () {
                PPbox._closeDialog(id);
            }
        }

        if (button.class === undefined) {
            button.class = 'btn btn-sm btn-dark';
        }

        return button;
    }

    static _buildButtons(id, button1, button2, button3) {
        let nb_buttons = 0,
            buttons = {};

        if (button1 !== undefined) {
            buttons[nb_buttons] = this._buildButton(id, button1);
            nb_buttons = nb_buttons+1;
        }
        if (button2 !== undefined) {
            buttons[nb_buttons] = this._buildButton(id, button2);
            nb_buttons = nb_buttons+1;
        }
        if (button3 !== undefined) {
            buttons[nb_buttons] = this._buildButton(id, button3);
        }

        return buttons;
    }

    static dialog(type, config, params) {
        let id = config.data('ppbox-id'),
            title = config.data('ppbox-title'),
            body = config.data('ppbox-body'),
            theme = config.data('ppbox-theme'),
            width = config.data('ppbox-width'),
            button1 = config.data('ppbox-button1'),
            button2 = config.data('ppbox-button2'),
            button3 = config.data('ppbox-button3');

        if (params !== undefined) {
            if (button1 !== undefined) {
                button1 = PPbox._buildButton(id, button1, params);
            }
            if (button2 !== undefined) {
                button2 = PPbox._buildButton(id, button2, params);
            }
            if (button3 !== undefined) {
                button3 = PPbox._buildButton(id, button3, params);
            }
        }

        if (type === 'confirm') {
            this.confirm(id, title, body, theme, width, button1, button2, button3);
        } else if (type === 'alert') {
            this.alert(id, title, body, theme, width, button1, button2, button3);
        }
    }

    static init() {
        let ppboxconfirm = $('.ppboxconfirm'),
            ppboxalert = $('.ppboxalert');

        ppboxconfirm.on('click', function () {
            PPbox.dialog('confirm', $(this));
        });

        ppboxalert.on('click', function () {
            PPbox.dialog('alert', $(this));
        });
    }

}
