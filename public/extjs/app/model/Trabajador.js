// Definición del modelo
Ext.define('MyApp.model.Trabajador', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'tra_ide', type: 'int' },
        { name: 'tra_cod', type: 'int' },
        { name: 'tra_nom', type: 'string' },
        { name: 'tra_pat', type: 'string' },
        { name: 'tra_mat', type: 'string' },
        { name: 'est_ado', type: 'int' }
    ]
});
