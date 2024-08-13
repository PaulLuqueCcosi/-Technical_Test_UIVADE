Ext.create('Ext.form.Panel', {
    title: 'Formulario de Trabajador',
    width: 400,
    bodyPadding: 10,
    defaultType: 'textfield',
    items: [
        {
            fieldLabel: 'Código',
            name: 'tra_cod',
            allowBlank: false
        },
        {
            fieldLabel: 'Nombre',
            name: 'tra_nom',
            allowBlank: false
        },
        {
            fieldLabel: 'Apellido Paterno',
            name: 'tra_pat',
            allowBlank: false
        },
        {
            fieldLabel: 'Apellido Materno',
            name: 'tra_mat',
            allowBlank: false
        }
    ],
    buttons: [
        {
            text: 'Guardar',
            formBind: true, // solo habilitado si el formulario es válido
            handler: function() {
                var form = this.up('form').getForm();
                if (form.isValid()) {
                    var values = form.getValues();
                    var store = Ext.getStore('trabajadorStore'); // Nombre del store
                    if (values.tra_ide) {
                        // Modificar existente
                        store.getById(values.tra_ide).set(values);
                    } else {
                        // Nuevo registro
                        store.add(values);
                    }
                    store.sync();
                    form.reset();
                    Ext.getCmp('trabajadores-grid').getStore().load();
                    this.up('form').hide(); // Oculta el formulario después de guardar
                }
            }
        },
        {
            text: 'Cancelar',
            handler: function() {
                this.up('form').hide(); // Oculta el formulario cuando se cancela
            }
        }
    ],
    renderTo: Ext.getBody(),
    hidden: true // Inicialmente oculto
});
