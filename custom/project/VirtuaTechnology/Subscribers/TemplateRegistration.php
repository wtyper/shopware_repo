<?php

namespace VirtuaTechnology\Subscribers;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs;

class TemplateRegistration implements SubscriberInterface{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return ['Enlight_Controller_Action_PreDispatch' => 'addTemplateDir'];
    }

    public function addTemplateDir(Enlight_Controller_ActionEventArgs $args): void
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/views');
    }
}
