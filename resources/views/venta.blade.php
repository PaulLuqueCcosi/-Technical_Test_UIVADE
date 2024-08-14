<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8" />
    <title>Modulo Cabecera y Detalle</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/resources/css/ext-all.css') }}" />
    <script type="text/javascript" src="{{ asset('extjs/bootstrap.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('resources/locale/ext-lang-es.js') }}" charset="utf-8"></script>
    <script>
        Ext.onReady(function () {
            Ext.QuickTips.init();

            // Modelos
            Ext.define('MyApp.model.Venta', {
                extend: 'Ext.data.Model',
                fields: [
                    { name: 'ven_ide', type: 'int' },
                    { name: 'ven_ser', type: 'string' },
                    { name: 'ven_num', type: 'string' },
                    { name: 'ven_cli', type: 'string' },
                    { name: 'ven_mon', type: 'float' }
                ]
            });

            Ext.define('MyApp.model.VentaDetalle', {
                extend: 'Ext.data.Model',
                fields: [
                    { name: 'v_d_ide', type: 'int' },
                    { name: 'ven_ide', type: 'int' },
                    { name: 'v_d_pro', type: 'string' },
                    { name: 'v_d_uni', type: 'float' },
                    { name: 'v_d_can', type: 'float' },
                    { name: 'v_d_tot', type: 'float' },
                    { name: 'est_ado', type: 'int' }
                ]
            });

            // Stores
            var ventaStore = Ext.create('Ext.data.Store', {
                model: 'MyApp.model.Venta',
                autoLoad: true,
                pageSize: 10,
                proxy: {
                    type: 'rest',
                    url: '/api/ventas',
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
                }
            });

            var ventaDetalleStore = Ext.create('Ext.data.Store', {
                model: 'MyApp.model.VentaDetalle',
                autoLoad: false,
                proxy: {
                    type: 'rest',
                    url: '/api/ventas/detalles',
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

            // formualrio
            var formularioVenta = Ext.create('Ext.form.Panel', {
                title: 'Formulario de Venta',
                width: "100%",
                bodyPadding: 10,
                defaultType: 'textfield',
                items: [
                    { name: 'ven_ide', xtype: 'hidden' },
                    { fieldLabel: 'Serie', name: 'ven_ser' },
                    { fieldLabel: 'Número', name: 'ven_num' },
                    { fieldLabel: 'Cliente', name: 'ven_cli' },
                    { fieldLabel: 'Monto', name: 'ven_mon', xtype: 'numberfield', decimalPrecision: 2 }
                ],
                buttons: [
                    {
                        text: 'Guardar',
                        formBind: true,
                        handler: function () {
                            var form = formularioVenta.getForm();
                            if (form.isValid()) {
                                var values = form.getValues();
                                var url = values.ven_ide ? '/api/ventas/' + values.ven_ide : '/api/ventas';
                                var method = values.ven_ide ? 'PUT' : 'POST';

                                form.submit({
                                    url: url,
                                    method: method,
                                    success: function () {
                                        ventaStore.load();
                                        formularioVenta.reset();
                                        formularioVenta.hide();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert('Error', action.result.message || 'No se pudo guardar la venta.');
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: 'Cancelar',
                        handler: function () {
                            formularioVenta.getForm().reset();
                            // panel2.hide();
                        }
                    },
                    {
                        text: 'Cerrar',
                        handler: function () {
                            panel2.hide(); // Aquí se oculta el panel2
                            formularioVenta.getForm().reset();
                            formularioDetalleVenta.getForm().reset();
                        }
                    }
                ],
                // hidden: true
            });

            // Formulario de Detalle de Venta
            var formularioDetalleVenta = Ext.create('Ext.form.Panel', {
                title: 'Formulario de Detalle de Venta',
                width: "100%",
                bodyPadding: 10,
                defaultType: 'textfield',
                items: [
                    { name: 'v_d_ide', xtype: 'hidden' },
                    { name: 'ven_ide', xtype: 'hidden' }, // ID de la venta asociado
                    { fieldLabel: 'Producto', name: 'v_d_pro' },
                    { fieldLabel: 'Unidad', name: 'v_d_uni', xtype: 'numberfield', decimalPrecision: 2 },
                    { fieldLabel: 'Cantidad', name: 'v_d_can', xtype: 'numberfield', decimalPrecision: 2 },
                    { fieldLabel: 'Total', name: 'v_d_tot', xtype: 'numberfield', decimalPrecision: 2 }
                ],
                buttons: [
                    {
                        text: 'Guardar',
                        formBind: true,
                        handler: function () {
                            var form = formularioDetalleVenta.getForm();
                            if (form.isValid()) {
                                var values = form.getValues();
                                var url = values.v_d_ide ? '/api/ventas/' + values.ven_ide + '/detalles/' + values.v_d_ide : '/api/ventas/' + values.ven_ide + '/detalles';
                                var method = values.v_d_ide ? 'PUT' : 'POST';

                                form.submit({
                                    url: url,
                                    method: method,
                                    success: function () {
                                        ventaDetalleStore.load();
                                        formularioDetalleVenta.reset();
                                        formularioDetalleVenta.hide();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert('Error', action.result.message || 'No se pudo guardar el detalle de la venta.');
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: 'Cancelar',
                        handler: function () {
                            formularioDetalleVenta.getForm().reset();
                            panel3.hide();
                        }
                    }
                ]
            });

            // grillas
            // Definición del grid para la Cabecera de Ventas
            var gridCabeceraVentas = Ext.create('Ext.grid.Panel', {
                title: 'Cabecera de Ventas',
                store: ventaStore,
                columns: [
                    { text: 'ID', dataIndex: 'ven_ide', flex: 1 },
                    { text: 'Serie', dataIndex: 'ven_ser', flex: 1 },
                    { text: 'Número', dataIndex: 'ven_num', flex: 1 },
                    { text: 'Cliente', dataIndex: 'ven_cli', flex: 2 },
                    { text: 'Monto', dataIndex: 'ven_mon', flex: 1 }
                ],
                height: 350,
                width: '100%',
                listeners: {
                    selectionchange: function (selModel, selected) {
                        var selectedVenta = selected[0];
                        if (selectedVenta) {
                            ventaDetalleStore.getProxy().url = '/api/ventas/' + selectedVenta.get('ven_ide') + '/detalles';
                            ventaDetalleStore.load();
                        }
                    }
                },
                bbar: {
                    xtype: 'pagingtoolbar',
                    store: ventaStore,
                    displayInfo: true
                },
                tbar: [
                    {
                        text: 'Nuevo',
                        handler: function () {
                            panel2.show();
                        }
                    },
                    {
                        text: 'Modificar',
                        handler: function () {
                            var record = gridCabeceraVentas.getSelectionModel().getSelection()[0];
                            if (record) {
                                formularioVenta.getForm().loadRecord(record); // Carga el registro en el formulario
                                panel2.show(); // Muestra el panel de formularios
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione una venta para modificar.');
                            }
                        }
                    },
                    {
                        text: 'Eliminar',
                        handler: function () {
                            var record = gridCabeceraVentas.getSelectionModel().getSelection()[0];
                            if (record) {
                                Ext.Msg.confirm('Eliminar Venta',
                                    '¿Está seguro de que desea eliminar esta venta?',
                                    function (button) {
                                        if (button === 'yes') {
                                            Ext.Ajax.request({
                                                url: '/api/ventas/' + record.get('ven_ide'),
                                                method: 'DELETE',
                                                success: function () {
                                                    ventaStore.load();
                                                    ventaDetalleStore.load();
                                                },
                                                failure: function () {
                                                    Ext.Msg.alert('Error', 'No se pudo eliminar la venta.');
                                                }
                                            });
                                        }
                                    }
                                );
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione una venta para eliminar.');
                            }
                        }
                    }
                ]
            });

            // Definición del grid para los Detalles de Venta
            var gridDetallesVentas = Ext.create('Ext.grid.Panel', {
                title: 'Detalles de Venta',
                store: ventaDetalleStore,
                columns: [
                    { text: 'ID', dataIndex: 'v_d_ide', flex: 1 },
                    { text: 'ID - Venta', dataIndex: 'ven_ide', flex: 1 },
                    { text: 'Producto', dataIndex: 'v_d_pro', flex: 2 },
                    { text: 'Unidad', dataIndex: 'v_d_uni', flex: 1 },
                    { text: 'Cantidad', dataIndex: 'v_d_can', flex: 1 },
                    { text: 'Total', dataIndex: 'v_d_tot', flex: 1 }
                ],
                bbar: {
                    xtype: 'pagingtoolbar',
                    store: ventaDetalleStore,
                    displayInfo: true
                },
                tbar: [
                    {
                        text: 'Nuevo Detalle',
                        handler: function () {
                            panel2.show();
                            formularioDetalleVenta.show();
                            formularioDetalleVenta.getForm().reset(); // Limpiar el formulario
                        }
                    },
                    {
                        text: 'Modificar Detalle',
                        handler: function () {
                            var record = gridDetallesVentas.getSelectionModel().getSelection()[0];
                            if (record) {
                                formularioDetalleVenta.loadRecord(record);
                                formularioVenta.loadRecord(gridCabeceraVentas.getSelectionModel().getSelection()[0]);
                                panel2.show();
                                formularioDetalleVenta.show();
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione un detalle de venta para modificar.');
                            }
                        }
                    },
                    {
                        text: 'Eliminar Detalle',
                        handler: function () {
                            var record = gridDetallesVentas.getSelectionModel().getSelection()[0];
                            if (record) {
                                Ext.Msg.confirm('Eliminar Detalle',
                                    '¿Está seguro de que desea eliminar este detalle de venta?',
                                    function (button) {
                                        if (button === 'yes') {
                                            Ext.Ajax.request({
                                                url: '/api/ventas/detalles/' + record.get('v_d_ide'),
                                                method: 'DELETE',
                                                success: function () {
                                                    ventaDetalleStore.load();
                                                },
                                                failure: function () {
                                                    Ext.Msg.alert('Error', 'No se pudo eliminar el detalle de la venta.');
                                                }
                                            });
                                        }
                                    }
                                );
                            } else {
                                Ext.Msg.alert('Error', 'Seleccione un detalle de venta para eliminar.');
                            }
                        }
                    }
                ],
                height: 350,
                width: '100%'
            });

            // Paneles
            var panel1 = Ext.create('Ext.panel.Panel', {
                title: 'Ventas',
                width: 700,
                height: 700,
                layout: 'vbox',
                items: [
                    gridCabeceraVentas,
                    gridDetallesVentas
                ]
            });

            // Paneles
            var panel2 = Ext.create('Ext.panel.Panel', {
                title: 'Formularios',
                hidden: true,
                width: 600,
                height: 400,
                layout: 'vbox',
                items: [
                    formularioVenta,
                    formularioDetalleVenta

                ]
            });



            // Contenedor principal
            Ext.create('Ext.container.Viewport', {
                layout: 'hbox',
                items: [
                    panel1,
                    panel2,
                ]
            });
        });
    </script>
</head>

<body>
</body>

</html>
