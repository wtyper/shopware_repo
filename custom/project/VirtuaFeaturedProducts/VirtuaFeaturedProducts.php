<?php

namespace VirtuaFeaturedProducts;
use Exception;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
class VirtuaFeaturedProducts extends Plugin
{
    /**
     * @param InstallContext $context
     * @throws Exception
     */
    public function install(InstallContext $context)
    {
        /** @var CrudService $service */
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes',
            'virtua_is_featured',
            TypeMapping::TYPE_BOOLEAN,
            [
                'label' => 'Is featured',
                'displayInBackend' => true,
                'translatable' => true
            ],
            'virtua_is_featured',
            null,
            false
        );
    }
    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'virtua_is_featured');
    }
}
