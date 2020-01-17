Ext.define('Shopware.apps.VirtuaTechnology.view.list.Bundle', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.bundle-listing-grid',
    region: 'center',

    configure: function () {
        return {
            detailWindow: 'Shopware.apps.VirtuaTechnology.view.detail.Window',
            columns: {
                name: {},
                description: {}
            }
        };
    }
});
