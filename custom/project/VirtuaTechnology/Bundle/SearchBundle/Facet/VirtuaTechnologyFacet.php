<?php

namespace VirtuaTechnology\Bundle\SearchBundle\Facet;

use Shopware\Bundle\SearchBundle\FacetInterface;

class VirtuaTechnologyFacet implements FacetInterface
{
    public function getName(): string
    {
        return 'virtua_technology';
    }
}
