Ext.define('MyApp.controller.Trabajadores', {
    extend: 'Ext.app.Controller',
    stores: ['Trabajadores'],
    models: ['Trabajador'],
    views: ['trabajador.List', 'trabajador.Edit'],

    init: function () {
        this.control({
            'trabajadorlist button[action=add]': {
                click: this.addTrabajador
            },
            'trabajadorlist button[action=edit]': {
                click: this.editTrabajador
            },
            'trabajadorlist button[action=delete]': {
                click: this.deleteTrabajador
            },
            'trabajadoredit button[action=save]': {
                click: this.updateTrabajador
            }
        });
    },

    addTrabajador: function () {
        var view = Ext.widget('trabajadoredit');
        view.down('form').getForm().reset();
    },

    editTrabajador: function (button) {
        var grid = button.up('grid'),
            record = grid.getSelectionModel().getSelection()[0];
        if (record) {
            var view = Ext.widget('trabajadoredit');
            view.down('form').loadRecord(record);
        }
    },

    updateTrabajador: function (button) {
        var win = button.up('window'),
            form = win.down('form').getForm(),
            record = form.getRecord(),
            values = form.getValues();

        if (record) {
            record.set(values);
        } else {
            this.getTrabajadoresStore().add(values);
        }
        win.close();
    },

    deleteTrabajador: function (button) {
        var grid = button.up('grid'),
            record = grid.getSelectionModel().getSelection()[0];

        if (record) {
            record.set('est_ado', 0); // Cambia el estado a 0 para eliminación lógica
            this.getTrabajadoresStore().sync();
        }
    }
});
