<?php
declare(strict_types=1);
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VirtuaTechnology\Bundle\SearchBundle\Condition;

use Assert\Assertion;
use Shopware\Bundle\SearchBundle\ConditionInterface;

class VirtuaTechnologyCondition implements ConditionInterface
{
    /**
     * @var int[] $virtuaTechnologies
     */
    private $virtuaTechnologies;

    public function __construct(array $virtuaTechnologies)
    {
        Assertion::allIntegerish($virtuaTechnologies);
        $this->virtuaTechnologies = array_map('intval', $virtuaTechnologies);
    }

    public function getName(): string
    {
        return 'virtua_technology';
    }

    /**
     * @return int[]
     */
    public function getVirtuaTechnologies(): array
    {
        return $this->virtuaTechnologies;
    }
}
