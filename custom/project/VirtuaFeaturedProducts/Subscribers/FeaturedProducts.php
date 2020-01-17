<?php
namespace VirtuaFeaturedProducts\Subscribers;

use Doctrine\DBAL\Connection;
use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;
use Enlight_Event_EventArgs;
use PDO;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ListProductService;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Components\DependencyInjection\Container;

class FeaturedProducts implements SubscriberInterface
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var ListProductService
     */
    private $listProductService;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var array
     */
    private $config;
    public function __construct(
        Connection $connection,
        ListProductServiceInterface $listProductService,
        Container $container
    ) {
        $this->container = $container;
        $this->connection = $connection;
        $this->config = $this->container->get('shopware.plugin.cached_config_reader')->getByPluginName('VirtuaFeaturedProducts',
            Shopware()->Shop());
        $this->listProductService = $listProductService;
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
        if (!$this->config['virtua_display_featured_products'] || !$subject->View()) {
            return;
        }
        $article = $subject->View()->getAssign('sArticle');
        $featuredArticles = $this->listProductService->getList($this->fetchFeaturedArticleIDs($article['articleID']),
            $this->container->get('shopware_storefront.context_service')->getContext());
        if ($article && $featuredArticles) {
            $featuredArticlesNormalized = [];
            foreach ($featuredArticles as $featuredArticle) {
                $featuredArticlesNormalized[] = $this->normalizeArticle($featuredArticle);
            }
            $subject->View()->assign('sFeaturedArticles', $featuredArticlesNormalized);
        }
    }
    /**
     * @param int $productID
     * @return array
     */
    public function fetchFeaturedArticleIDs(int $productID): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('d.ordernumber')
            ->from('s_articles', 'p')
            ->innerJoin('p', 's_articles_details', 'd', 'd.articleID = p.id')
            ->innerJoin('p', 's_articles_attributes', 'a', 'a.articledetailsID = d.id')
            ->where('a.virtua_is_featured = 1')
            ->setMaxResults($this->config['virtua_number_of_products'] ?? null)
            ->andWhere('p.id != :product_id')
            ->setParameter('product_id', $productID)
            ->execute()
            ->fetchAll(PDO::FETCH_COLUMN);
    }
    /**
     * @param ListProduct $article
     * @return array
     */
    private function normalizeArticle(ListProduct $article): array
    {
        $cover = $article->getCover();
        $normalizedArticle = [
            'articleName' => $article->getName(),
            'linkDetails' => 'shopware.php?sViewport=detail&sArticle=' . $article->getId()
        ];
        if ($cover) {
            $normalizedArticle['image'] = [
                'thumbnails' => [
                    0 => ['sourceSet' => $cover->getThumbnails()[0]->getSource()],
                    1 => ['sourceSet' => $cover->getThumbnails()[1]->getSource()]
                ]
            ];
        }
        return $normalizedArticle;
    }
}
