Ext.define('Shopware.apps.Performance.view.main.Technologies', {
    override: 'Shopware.apps.Performance.view.main.MultiRequestTasks',

    initComponent: function () {
        this.addProgressBar(
            {
                initialText: 'Technologies URLs',
                progressText: '[0] of [1] technology URLs',
                requestUrl: '{url controller=technologies action=generateSeoUrl}'
            },
            'technologies',
            'seo'
        );

        this.callParent(arguments);
    }
});
