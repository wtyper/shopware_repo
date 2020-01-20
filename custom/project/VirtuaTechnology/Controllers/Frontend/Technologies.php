<?php

use Doctrine\DBAL\Query\QueryBuilder;

class Shopware_Controllers_Frontend_Technologies extends Enlight_Controller_Action
{
    public function preDispatch()
    {
        $pluginBasePath = $this->container->getParameter('virtua_technology.plugin_dir');
        $this->View()->addTemplateDir($pluginBasePath . '/Resources/views');
    }

    public function indexAction()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $technologies = $queryBuilder
            ->select('vt.id, vt.name, vt.description, vt.url', 'm.path as image')
            ->from('s_virtua_technology', 'vt')
            ->leftJoin('vt', 's_media', 'm', 'm.id = vt.file')
            ->execute()
            ->fetchAll();

        $this->View()->assign('sTechnologies', $technologies);
    }

    public function detailAction()
    {
        $wordId = $this->Request()->getParam('technology_id');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $technology = $queryBuilder
            ->select('vt.id, vt.name, vt.description, vt.url', 'm.path as image')
            ->from('s_virtua_technology', 'vt')
            ->leftJoin('vt', 's_media', 'm', 'm.id = vt.file')
            ->where('vt.id = :id')
            ->setParameter(':id', $wordId)
            ->execute()
            ->fetch();

        $this->View()->assign($technology);
    }


}
