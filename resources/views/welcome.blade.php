<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8" />
    <title>Ejemplo Grid de Trabajadores</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/resources/css/ext-all.css') }}" />
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" /> -->
</head>

<body>
    <div id="trabajadores-grid"></div>

    <!-- Scripts de ExtJS -->
    <script type="text/javascript" src="{{ asset('extjs/bootstrap.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('resources/locale/ext-lang-es.js') }}" charset="utf-8"></script>

    <!-- Scripts de la aplicación -->
    <script type="text/javascript" src="{{ asset('extjs/app/model/Trabajador.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('extjs/app/store/Trabajadores.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('extjs/app/controller/Trabajadores.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('extjs/app/view/trabajador/Edit.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('extjs/app/view/trabajador/List.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        Ext.onReady(function () {
            Ext.QuickTips.init();

            // Inicializa la aplicación ExtJS
            Ext.application({
                name: 'MyApp',
                appFolder: 'extjs/app',
                controllers: ['Trabajadores'],
                launch: function () {
                    Ext.create('MyApp.view.trabajador.List', {
                        renderTo: 'trabajadores-grid'
                    });
                }
            });
        });
    </script>
</body>

</html>
