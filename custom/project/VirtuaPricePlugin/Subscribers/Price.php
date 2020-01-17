<?php
namespace VirtuaPricePlugin\Subscribers;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Symfony\Component\DependencyInjection\Container;

class Price implements SubscriberInterface
{
    /**
     * @var bool
     */
    private $hidePriceForAnon;

    public function __construct(
        Container $container
    )
    {
        $this->hidePriceForAnon = (bool)$container->get('shopware.plugin.cached_config_reader')
                ->getByPluginName('PricePlugin', Shopware()->Shop())['hide_price_for_anon'] &&
            !Shopware()->Modules()->Admin()->sCheckUser();
    }

    /**
     * @return array|void
     */
    public static function getSubscribedEvents(): ?array
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail' => 'onFrontendAction',
            'Enlight_Controller_Action_PostDispatch_Frontend_Listing' => 'onFrontendAction',
            'Enlight_Controller_Action_PostDispatch_Widgets_Listing' => 'onFrontendAction'
        ];
    }

    public function onFrontendAction(Enlight_Event_EventArgs $args): void
    {
        $args->getSubject()->View()->assign('hidePrice', $this->hidePriceForAnon);
    }
}
