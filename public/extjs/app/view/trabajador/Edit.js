Ext.define('MyApp.view.trabajador.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.trabajadoredit',
    title: 'Editar/Agregar Trabajador',
    layout: 'fit',
    autoShow: true,

    initComponent: function () {
        this.items = [{
            xtype: 'form',
            items: [
                { name: 'tra_cod', fieldLabel: 'Código', xtype: 'textfield' },
                { name: 'tra_nom', fieldLabel: 'Nombre', xtype: 'textfield' },
                { name: 'tra_pat', fieldLabel: 'Apellido Paterno', xtype: 'textfield' },
                { name: 'tra_mat', fieldLabel: 'Apellido Materno', xtype: 'textfield' }
            ]
        }];

        this.buttons = [
            { text: 'Guardar', action: 'save' },
            { text: 'Cancelar', scope: this, handler: this.close }
        ];

        this.callParent(arguments);
    }
});
