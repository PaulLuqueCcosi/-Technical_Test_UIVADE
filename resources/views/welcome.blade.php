<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8" />
    <title>Ejemplo Grid de Trabajadores</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/resources/css/ext-all.css') }}" />
    <script type="text/javascript" src="{{ asset('extjs/bootstrap.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('resources/locale/ext-lang-es.js') }}" charset="utf-8"></script>
    <script>
        Ext.onReady(function () {
            Ext.QuickTips.init();

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

            // Grid para mostrar los trabajadores
            Ext.create('Ext.grid.Panel', {
                title: 'Lista de Trabajadores',
                store: trabajadorStore,
                columns: [
                    { text: 'ID', dataIndex: 'tra_ide', flex: 1 },
                    { text: 'Código', dataIndex: 'tra_cod', flex: 1 },
                    { text: 'Nombre', dataIndex: 'tra_nom', flex: 2 },
                    { text: 'Apellido Paterno', dataIndex: 'tra_pat', flex: 2 },
                    { text: 'Apellido Materno', dataIndex: 'tra_mat', flex: 2 }
                ],
                height: 400,
                width: 600,
                renderTo: Ext.getBody()
            });
        });
    </script>
</head>

<body>
</body>

</html>
