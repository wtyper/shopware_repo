Ext.define('Shopware.apps.VirtuaTechnology.model.Bundle', {
    extend: 'Shopware.data.Model',

    configure: function () {
        return {
            controller: 'VirtuaTechnology',
            detail: 'Shopware.apps.VirtuaTechnology.view.detail.Bundle'
        };
    },

    fields: [
        { name: 'id', type: 'int', useNull: true },
        { name: 'name', type: 'string' },
        { name: 'description', type: 'string' },
        { name: 'file', type: 'string' },
    ],
    associations: [{
        relation: 'ManyToOne',
        field: 'file',
        type: 'hasMany',
        model: 'Shopware.apps.Base.model.Media',
        name: 'getMedia',
        associationKey: 'media'
    }]
});
