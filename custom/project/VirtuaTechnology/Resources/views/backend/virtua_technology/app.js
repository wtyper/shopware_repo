Ext.define('Shopware.apps.VirtuaTechnology', {
    extend: 'Enlight.app.SubApplication',

    name: 'Shopware.apps.VirtuaTechnology',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: ['Main'],

    views: [
        'list.Window',
        'list.Bundle',

        'detail.Bundle',
        'detail.Window'
    ],

    models: ['Bundle'],
    stores: ['Bundle'],

    launch: function () {
        return this.getController('Main').mainWindow;
    }
});
