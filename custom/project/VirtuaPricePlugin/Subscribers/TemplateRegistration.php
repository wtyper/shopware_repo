<?php

namespace VirtuaPricePlugin\Subscribers;
use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager as TemplateManager;
class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginPath;
    /**
     * @var TemplateManager
     */
    private $templateManager;
    /**
     * @param string $pluginPath
     * @param TemplateManager $templateManager
     */
    public function __construct($pluginPath, TemplateManager $templateManager)
    {
        $this->pluginPath = $pluginPath;
        $this->templateManager = $templateManager;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch'
        ];
    }
    /**
     * Registers the template directory globally for each request
     */
    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginPath . '/Resources/views/');
    }
}
