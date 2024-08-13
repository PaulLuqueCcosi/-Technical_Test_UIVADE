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
    tbar: [
        { text: 'Nuevo', action: 'add', iconCls: 'icon-add' },
        { text: 'Modificar', action: 'edit', iconCls: 'icon-edit' },
        { text: 'Eliminar', action: 'delete', iconCls: 'icon-delete' }
    ],
    height: 400,
    width: 600,
    renderTo: Ext.getBody()
});
