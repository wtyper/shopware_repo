<?php

namespace VirtuaTechnology\Subscribers;

use Doctrine\DBAL\Connection;
use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;
use Enlight_Event_EventArgs;

class ArticleTechnologies implements SubscriberInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * ArticleTechnologies constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail' => 'onFrontendListing'
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     * @return void
     */
    public function onFrontendListing(Enlight_Event_EventArgs $args): void
    {
        /** @var Enlight_Controller_Action $subject */
        $subject = $args->getSubject();
        $article = $subject->View()->getAssign('sArticle');
        if ($article['virtua_technology'] && !empty($technologyIDs = explode('|', $article['virtua_technology']))) {
            $article['virtua_technology'] = $this->getArticleTechnologies(array_filter($technologyIDs));
            $subject->View()->assign('sArticle', $article);
        }
    }

    /**
     * @param array $ids
     * @return array
     */
    private function getArticleTechnologies(array $ids): array
    {
        return $this->connection->createQueryBuilder()
            ->select('vt.id, vt.name, vt.description, vt.url, m.path as image')
            ->from('s_virtua_technology', 'vt')
            ->leftJoin('vt', 's_media', 'm', 'vt.file = m.id')
            ->where('vt.id IN (:ids)')
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll();
    }
}
