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
                autoLoad: true,
                proxy: {
                    type: 'rest',
                    url: '/api/trabajadores',
                    reader: {
                        type: 'json',
                        root: 'data'
                    },
                    writer: {
                        type: 'json',
                        writeAllFields: true
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
                    { fieldLabel: 'Código', name: 'tra_cod', xtype: 'numberfield', minValue: 0 },
                    { fieldLabel: 'Nombre', name: 'tra_nom' },
                    { fieldLabel: 'Apellido Paterno', name: 'tra_pat' },
                    { fieldLabel: 'Apellido Materno', name: 'tra_mat' }
                ],
                buttons: [
                    {
                        text: 'Guardar',
                        handler: function () {
                            var form = formPanel.getForm();
                            if (form.isValid()) {
                                var values = form.getValues();
                                // Convierte 'tra_cod' a entero
                                values.tra_cod = parseInt(values.tra_cod, 10);
                                var url = form.isValid() ? (values.tra_ide ? '/api/trabajadores/' + values.tra_ide : '/api/trabajadores') : '';
                                var method = values.tra_ide ? 'PUT' : 'POST';

                                form.submit({
                                    url: url,
                                    method: method,
                                    params: values,
                                    success: function (form, action) {
                                        trabajadorStore.load();
                                        form.reset();
                                        formPanel.hide();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert('Error', 'No se pudo guardar el trabajador.');
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
                height: 400,
                width: 600,
                listeners: {
                    itemclick: function (grid, record) {
                        formPanel.getForm().loadRecord(record);
                        formPanel.setTitle('Modificar Trabajador');
                        formPanel.show();
                    }
                }
            });

            // Botones para las acciones
            var buttonsPanel = Ext.create('Ext.panel.Panel', {
                layout: 'hbox',
                items: [
                    {
                        xtype: 'button',
                        text: 'Nuevo',
                        handler: function () {
                            formPanel.getForm().reset();
                            formPanel.setTitle('Nuevo Trabajador');
                            formPanel.show();
                        }
                    },
                    {
                        xtype: 'button',
                        text: 'Modificar',
                        handler: function () {
                            var record = grid.getSelectionModel().getSelection()[0];
                            if (record) {
                                formPanel.getForm().loadRecord(record);
                                formPanel.setTitle('Modificar Trabajador');
                                formPanel.show();
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione un trabajador para modificar.');
                            }
                        }
                    },
                    {
                        xtype: 'button',
                        text: 'Eliminar',
                        handler: function () {
                            var record = grid.getSelectionModel().getSelection()[0];
                            if (record) {
                                Ext.Ajax.request({
                                    url: '/api/trabajadores/' + record.get('tra_ide'),
                                    method: 'PUT',
                                    jsonData: { est_ado: 1 },
                                    success: function () {
                                        trabajadorStore.load();
                                    },
                                    failure: function () {
                                        Ext.Msg.alert('Error', 'No se pudo eliminar el trabajador.');
                                    }
                                });
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione un trabajador para eliminar.');
                            }
                        }
                    }
                ]
            });

            // Contenedor principal
            Ext.create('Ext.container.Viewport', {
                layout: 'vbox',
                items: [
                    buttonsPanel,
                    grid,
                    formPanel
                ]
            });
        });
    </script>
</head>

<body>
</body>

</html>
