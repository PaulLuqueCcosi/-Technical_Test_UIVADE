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
        Ext.Msg.alert('Agregar Trabajador', 'Botón "Agregar" presionado. Se debería abrir el formulario para agregar un nuevo trabajador.');
    },

    editTrabajador: function (button) {
        Ext.Msg.alert('Editar Trabajador', 'Botón "Editar" presionado. Se debería abrir el formulario con los datos del trabajador seleccionado.');
    },

    updateTrabajador: function (button) {
        Ext.Msg.alert('Guardar Trabajador', 'Botón "Guardar" presionado. Se deberían guardar los cambios del trabajador.');
    },

    deleteTrabajador: function (button) {
        Ext.Msg.alert('Eliminar Trabajador', 'Botón "Eliminar" presionado. Se debería cambiar el estado del trabajador a eliminado.');
    }
});
