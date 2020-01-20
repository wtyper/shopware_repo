<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VirtuaTechnology;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Tools\SchemaTool;
use Enlight_Controller_ActionEventArgs;
use Enlight_Event_EventArgs;
use Exception;
use PDO;
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
     * @param Enlight_Event_EventArgs $args
     * @return mixed
     */
    public function filterParameterQuery(Enlight_Event_EventArgs $args)
    {
        $orgQuery = $args->getReturn();
        $query = $args->getQuery();

        if ($query['controller'] === 'technologies' && isset($query['technology_id'])) {
            $orgQuery['technology_id'] = $query['technology_id'];
        }

        return $orgQuery;
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
    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function loadPerformanceExtension(Enlight_Controller_ActionEventArgs $args)
    {
        $subject = $args->getSubject();
        $request = $subject->Request();

        if ($request->getActionName() !== 'load') {
            return;
        }

        $subject->View()->addTemplateDir($this->getPath() . '/Resources/views/');
        $subject->View()->extendsTemplate('backend/performance/view/technologies.js');
    }

    /**
     * @param Enlight_Event_EventArgs $args
     * @return mixed
     */
    public function addTechnologiesCount(Enlight_Event_EventArgs $args)
    {
        $counts = $args->getReturn();

        /** @var QueryBuilder $dbalQueryBuilder */
        $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $technologiesCount = $dbalQueryBuilder->select('COUNT(vt.id)')
            ->from('s_virtua_technology', 'vt')
            ->execute()
            ->fetchAll(PDO::FETCH_COLUMN);

        $counts['technologies'] = $technologiesCount;

        return $counts;
    }
}
