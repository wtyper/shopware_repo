<?php

namespace VirtuaTechnology\Bundle\SearchBundle;

use Enlight_Controller_Request_RequestHttp as Request;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use VirtuaTechnology\Bundle\SearchBundle\Condition\VirtuaTechnologyCondition;
use VirtuaTechnology\Bundle\SearchBundle\Facet\VirtuaTechnologyFacet;

class CriteriaRequestHandler implements CriteriaRequestHandlerInterface
{
    public function handleRequest(
        Request $request,
        Criteria $criteria,
        ShopContextInterface $context
    ): void {
        if ($request->has('virtua_technology')) {
            $virtuaTechnologies = explode(
                '|',
                $request->getParam('virtua_technology')
            );
            if (!empty($virtuaTechnologies)) {
                $criteria->addCondition(new VirtuaTechnologyCondition($virtuaTechnologies));
            }
        }

        $criteria->addFacet(new VirtuaTechnologyFacet());
    }
}
