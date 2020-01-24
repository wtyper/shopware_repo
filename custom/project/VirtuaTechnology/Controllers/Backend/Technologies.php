<?php

use Doctrine\DBAL\Query\QueryBuilder;

class Shopware_Controllers_Backend_Technologies extends Shopware_Controllers_Backend_ExtJs
{
    public function generateSeoUrlAction()
    {
        $shopId = $this->Request()->getParam('shopId');
        $offset = $this->Request()->getParam('offset');
        $limit = $this->Request()->getParam('limit', 50);

        /** @var Shopware_Components_SeoIndex $seoIndex */
        $seoIndex = $this->container->get('SeoIndex');
        $seoIndex->registerShop($shopId);

        /** @var sRewriteTable $rewriteTableModule */
        $rewriteTableModule = $this->container->get('modules')->RewriteTable();
        $rewriteTableModule->baseSetup();
        $rewriteTableModule->sInsertUrl('sViewport=technologies', 'technologies/');

        /** @var QueryBuilder $dbalQueryBuilder */
        $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $words = $dbalQueryBuilder->select('vt.id, vt.url')
            ->from('s_virtua_technology', 'vt')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->execute()
            ->fetchAll(PDO::FETCH_KEY_PAIR);

        foreach ($words as $id => $slug) {
            $rewriteTableModule->sInsertUrl('sViewport=technologies&sAction=detail&technology_id=' . $id,
                'technologies/' . $slug);
        }

        $this->View()->assign(['success' => true]);
    }
}
