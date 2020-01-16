<?php
namespace VirtuaFeaturedProducts\Subscribers;
use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
class TemplateRegistration implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Inheritance_Template_Directories_Collected' => 'onTemplateDirectoriesCollect',
        ];
    }
    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onTemplateDirectoriesCollect(Enlight_Event_EventArgs $args): void
    {
        $dirs = $args->getReturn();
        $dirs['virtua_featured_products'] = __DIR__ . '/../Resources/views';
        $args->setReturn($dirs);
    }
}
