Ext.define('Shopware.apps.VirtuaTechnology.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.bundle-list-window',
    height: 450,
    title: 'Technology List',

    configure: function () {
        return {
            listingGrid: 'Shopware.apps.VirtuaTechnology.view.list.Bundle',
            listingStore: 'Shopware.apps.VirtuaTechnology.store.Bundle'
        };
    }
});
