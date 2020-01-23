<?php

namespace EstimatedDeliveryTime;

use Enlight_Controller_ActionEventArgs;
use Exception;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class EstimatedDeliveryTime extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'addTemplateDir',
        ];
    }

    /**
     * @param InstallContext $context
     * @throws Exception
     */
    public function install(InstallContext $context)
    {
        /** @var CrudService $service */
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes',
            'shipping_in',
            TypeMapping::TYPE_INTEGER,
            [
                'label' => 'Shipping in',
                'displayInBackend' => true,
            ],
            'virtua.shipping_in',
            null,
            1
        );
    }

    /**
     * @param UninstallContext $context
     * @throws Exception
     */
    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'shipping_in');
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function addTemplateDir(Enlight_Controller_ActionEventArgs $args): void
    {
        $args
            ->getSubject()
            ->View()
            ->addTemplateDir($this->getPath() . '/Resources/views');
    }
}
