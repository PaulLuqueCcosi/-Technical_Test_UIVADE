// Store para cargar los datos de la API
var trabajadorStore = Ext.create('Ext.data.Store', {
    model: 'MyApp.model.Trabajador',
    autoLoad: true,  // Carga los datos automáticamente al inicializar el store
    proxy: {
        type: 'rest',
        url: '/api/trabajadores',
        reader: {
            type: 'json',
            root: 'data'
        }
    }
});
