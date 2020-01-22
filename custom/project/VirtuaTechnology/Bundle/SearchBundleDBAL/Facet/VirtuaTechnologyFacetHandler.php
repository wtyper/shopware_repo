<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VirtuaTechnology\Bundle\SearchBundleDBAL\Facet;

use Doctrine\DBAL\Connection;
use Elasticsearch\Connections\ConnectionInterface;
use PDO;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\FacetInterface;
use Shopware\Bundle\SearchBundle\FacetResult\ValueListFacetResult;
use Shopware\Bundle\SearchBundle\FacetResult\ValueListItem;
use Shopware\Bundle\SearchBundleDBAL\PartialFacetHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilderFactoryInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use VirtuaTechnology\Bundle\SearchBundle\Facet\VirtuaTechnologyFacet;

class VirtuaTechnologyFacetHandler implements PartialFacetHandlerInterface
{
    /**
     * @var QueryBuilderFactoryInterface
     */
    private $queryBuilderFactory;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    public function __construct(QueryBuilderFactoryInterface $queryBuilderFactory, Connection $connection)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->connection = $connection;
    }

    public function supportsFacet(FacetInterface $facet): bool
    {
        return $facet instanceof VirtuaTechnologyFacet;
    }

    public function generatePartialFacet(
        FacetInterface $facet,
        Criteria $reverted,
        Criteria $criteria,
        ShopContextInterface $context
    ): ?ValueListFacetResult {
        $query = $this->queryBuilderFactory->createQuery($reverted, $context);
        $technologyIds = [];
        $result = array_filter($query
            ->select('productAttribute.virtua_technology')
            ->execute()
            ->fetchAll(PDO::FETCH_COLUMN)
        );
        array_map(static function ($el) use (&$technologyIds) {
            $technologyIds = array_merge($technologyIds, array_filter(explode('|', $el)));
        }, $result);
        if (!$technologies = $this->getTechnologies($technologyIds)) {
            return null;
        }
        $facetTechnologies = [];
        foreach ($technologies as $technology) {
            $facetTechnologies[] = new ValueListItem($technology['id'], $technology['name'], false);
        }

        return new ValueListFacetResult(
            $facet->getName(),
            true,
            'Virtua Technology',
            $facetTechnologies,
            'virtua_technology'
        );
    }

    /**
     * @param array $ids
     * @return array
     */
    private function getTechnologies(array $ids): array
    {
        return $this->connection->createQueryBuilder()
            ->select(['vt.id', 'vt.name'])
            ->from('s_virtua_technology', 'vt')
            ->leftJoin('vt', 's_media', 'm', 'vt.file = m.id')
            ->where('vt.id IN (:ids)')
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll();
    }
}
