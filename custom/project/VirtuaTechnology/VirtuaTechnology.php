<?php

namespace VirtuaTechnology;

use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class VirtuaTechnology extends Plugin{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $context): void
    {
        $this->updateSchema();
    }

    private function updateSchema(): void
    {

        $tool = new SchemaTool($this->container->get('models'));
        $classes = $this->getModelMetaData();

        try {
            $tool->dropSchema($classes);
        } catch (Exception $e){
        }
        $tool->createSchema($classes);
    }

    private function getModelMetaData(): array
    {
        return[$this->container->get('models')->getClassMetadata(Models\VirtuaTechnologyModel::class)];
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(UninstallContext $context)
    {
        $tool = new SchemaTool($this->container->get('models'));
        $classes = $this->getModelMetaData();
        $tool->dropSchema($classes);
    }
}
