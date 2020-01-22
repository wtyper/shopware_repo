<?php

namespace VirtuaTechnology;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
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

    /**
     * {@inheritdoc}
     * @throws ToolsException
     */
    private function updateSchema(): void
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);
        $schemaManager = $modelManager->getConnection()->getSchemaManager();
        foreach ($this->getModelMetaData() as $class) {
            if (!$schemaManager->tablesExist([$class->getTableName()])) {
                $tool->createSchema([$class]);
            } else {
                $tool->updateSchema([$class], true);
            }
        }
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
        if ($context->keepUserData()) {
            return;
        }
    }
}
