Ext.define('Shopware.apps.VirtuaTechnology.store.Bundle', {
    extend: 'Shopware.store.Listing',

    configure: function () {
        return {
            controller: 'VirtuaTechnology'
        };
    },
    model: 'Shopware.apps.VirtuaTechnology.model.Bundle'
});
