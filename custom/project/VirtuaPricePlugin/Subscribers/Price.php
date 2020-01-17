<?php
namespace VirtuaPricePlugin\Subscribers;
use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Shopware\Components\Plugin\CachedConfigReader;
/**
 * Class Price
 * @package VirtuaPricePlugin\Subscribers
 */
class Price implements SubscriberInterface
{
    /**
     * @var CachedConfigReader
     */
    private $pluginConfig;
    /**
     * Price constructor.
     * @param CachedConfigReader $cachedConfigReader
     */
    public function __construct(CachedConfigReader $cachedConfigReader)
    {
        $this->pluginConfig = $cachedConfigReader->getByPluginName('VirtuaPricePlugin', Shopware()->Shop());
    }
    /**
     * @return bool
     */
    public function hidePriceForAnon()
    {
        return $this->pluginConfig['hide_price_for_anon'] && !Shopware()->Modules()->Admin()->sCheckUser();
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
    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onFrontendAction(Enlight_Event_EventArgs $args): void
    {
        $args->getSubject()->View()->assign('hidePrice', $this->hidePriceForAnon());
    }
}
