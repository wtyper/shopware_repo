<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VirtuaTechnology\Bundle\SearchBundleDBAL\Condition;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use VirtuaTechnology\Bundle\SearchBundle\Condition\VirtuaTechnologyCondition;

class VirtuaTechnologyConditionHandler implements ConditionHandlerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * VirtuaTechnologyConditionHandler constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function supportsCondition(ConditionInterface $condition): bool
    {
        return $condition instanceof VirtuaTechnologyCondition;
    }

    /**
     * @param ConditionInterface $condition
     * @param QueryBuilder $query
     * @param ShopContextInterface $context
     */
    public function generateCondition(
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ): void {
        /** @var VirtuaTechnologyCondition $condition */
        $technologies = $condition->getVirtuaTechnologies();
        $products = (clone $query) # Grab products with technologies assigned
        ->select('product.id, productAttribute.virtua_technology')
            ->where('productAttribute.virtua_technology IS NOT NULL AND productAttribute.virtua_technology != ""')
            ->execute()
            ->fetchAll();
        $products = array_filter($products, static function ($el) use ($technologies) {
            # Check if product has at least one technology (from the filter) assigned
            return array_intersect($technologies, array_filter(explode('|', $el['virtua_technology'])));
        });
        if ($products) {
            $query
                ->where('product.id IN (:product_ids)')
                ->setParameter('product_ids', array_column($products, 'id'), Connection::PARAM_INT_ARRAY);
        }
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
