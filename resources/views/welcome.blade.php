<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8" />
    <title>Ejemplo Grid de Trabajadores</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/resources/css/ext-all.css') }}" />
    <script type="text/javascript" src="{{ asset('extjs/bootstrap.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('resources/locale/ext-lang-es.js') }}" charset="utf-8"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (opcional si se necesita JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

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
                autoLoad: true,
                pageSize: 10,
                proxy: {
                    type: 'rest',
                    url: '/api/trabajadores',
                    reader: {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'meta.total'
                    },
                    writer: {
                        type: 'json',
                        writeAllFields: true
                    },
                    extraParams: {
                        filter: '', // Parámetro de filtro inicial
                        page: 1,
                        // limit: 10
                    }
                },
                listeners: {
                    load: function (store, records, successful, operation) {
                        if (successful) {
                            store.totalCount = store.getProxy().getReader().rawData.meta.total;
                        }
                    }
                }
            });

            // Panel de formulario
            var formPanel = Ext.create('Ext.form.Panel', {
                title: 'Formulario de Trabajadores',
                width: 400,
                bodyPadding: 10,
                defaultType: 'textfield',
                items: [
                    { name: 'tra_ide', xtype: 'hidden' },
                    { fieldLabel: 'Código', name: 'tra_cod', xtype: 'numberfield', minValue: 0, allowBlank: false },
                    { fieldLabel: 'Nombre', name: 'tra_nom', allowBlank: false },
                    { fieldLabel: 'Apellido Paterno', name: 'tra_pat', allowBlank: false },
                    { fieldLabel: 'Apellido Materno', name: 'tra_mat', allowBlank: false }
                ],
                buttons: [
                    {
                        text: 'Guardar',
                        formBind: true,
                        disabled: true,
                        handler: function () {
                            var form = formPanel.getForm();
                            if (form.isValid()) {
                                var values = form.getValues();
                                values.tra_cod = parseInt(values.tra_cod, 10);
                                var url = values.tra_ide ? '/api/trabajadores/' + values.tra_ide : '/api/trabajadores';
                                var method = values.tra_ide ? 'PUT' : 'POST';

                                form.submit({
                                    url: url,
                                    method: method,
                                    success: function () {
                                        trabajadorStore.load(); // Recarga el store para actualizar la grilla
                                        form.reset();
                                        formPanel.hide();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert('Error', action.result.message || 'No se pudo guardar el trabajador.');
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: 'Cancelar',
                        handler: function () {
                            formPanel.getForm().reset();
                            formPanel.hide();
                        }
                    }
                ],
                hidden: true
            });

            // Grid para mostrar los trabajadores
            var grid = Ext.create('Ext.grid.Panel', {
                title: 'Lista de Trabajadores',
                store: trabajadorStore,
                columns: [
                    { text: 'ID', dataIndex: 'tra_ide', flex: 1 },
                    { text: 'Código', dataIndex: 'tra_cod', flex: 1 },
                    { text: 'Nombre', dataIndex: 'tra_nom', flex: 2 },
                    { text: 'Apellido Paterno', dataIndex: 'tra_pat', flex: 2 },
                    { text: 'Apellido Materno', dataIndex: 'tra_mat', flex: 2 }
                ],
                tbar: [

                    {
                        xtype: 'button',
                        text: 'Nuevo Trabajador',
                        handler: function () {
                            formPanel.getForm().reset();
                            formPanel.setTitle('Nuevo Trabajador');
                            formPanel.show();
                        }
                    },
                    {
                        xtype: 'button',
                        text: 'Eliminar Trabajador',
                        handler: function () {
                            var record = grid.getSelectionModel().getSelection()[0];
                            if (record) {
                                Ext.Msg.confirm('Eliminar Trabajador',
                                    '¿Está seguro de que desea eliminar este trabajador?',
                                    function (button) {
                                        if (button === 'yes') {
                                            Ext.Ajax.request({
                                                url: '/api/trabajadores/' + record.get('tra_ide'),
                                                method: 'DELETE',
                                                success: function () {
                                                    trabajadorStore.load();
                                                },
                                                failure: function () {
                                                    Ext.Msg.alert('Error', 'No se pudo eliminar el trabajador.');
                                                }
                                            });
                                        }
                                    }
                                );
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione un trabajador para eliminar.');
                            }
                        }
                    }
                ],
                bbar: {
                    xtype: 'pagingtoolbar',
                    store: trabajadorStore,
                    displayInfo: true
                },
                height: 400,
                width: 800,
                listeners: {
                    itemclick: function (grid, record) {
                        formPanel.getForm().loadRecord(record);
                        formPanel.setTitle('Modificar Trabajador');
                        formPanel.show();
                    }
                }
            });

            // Contenedor principal
            Ext.create('Ext.container.Viewport', {
                layout: 'vbox',
                items: [
                    grid,
                    formPanel
                ]
            });
        });
    </script>
</head>

<body>
    @include('partials.header')

</body>

</html>
