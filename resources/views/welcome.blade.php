<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8" />
    <title>EJEMPLO IUVADE</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/resources/css/ext-all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/example.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('extjs/ux/css/CheckHeader.css') }}" />
    <script type="text/javascript" src="{{ asset('extjs/bootstrap.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('resources/locale/ext-lang-es.js') }}" charset="utf-8"></script>
    <script>
        Ext.onReady(function(){
            Ext.QuickTips.init();

            Ext.create('Ext.form.Panel', {
                title: 'Contact Info',
                width: 300,
                bodyPadding: 10,
                renderTo: Ext.getBody(),
                items: [{
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: 'Name',
                    allowBlank: false
                }, {
                    xtype: 'textfield',
                    name: 'email',
                    fieldLabel: 'Email Address',
                    vtype: 'email'
                }]
            });

        });
    </script>
</head>
<body>
</body>
</html>
