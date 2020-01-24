<?php

namespace EstimatedDeliveryTime\Subscribers;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Enlight_Controller_Action;

class DetailDeliveryTime implements SubscriberInterface
{
    private const TIME_FORMAT = 'H:i:s';

    /**
     * @var array
     */
    private $notShipping;
    /**
     * @var false|int
     */
    private $holidaysEnd;
    /**
     * @var false|int
     */
    private $shipsUntil;
    /**
     * @var false|int
     */
    private $holidaysStart;
    /**
     * @var bool
     */
    private $shipToday;
    /**
     * @var false|int
     */
    private $shippingDate;
    /**
     * @var int
     */
    private $shippingIn;
    /**
     * @var bool
     */
    private $holidays;

    public function __construct(ContainerInterface $container)
    {
        $this->createDateAndTimeVariables($container
            ->get('shopware.plugin.cached_config_reader')
            ->getByPluginName('EstimatedDeliveryTime', Shopware()->Shop()));
    }

    /**
     * @param array $config
     */
    public function createDateAndTimeVariables(array $config): void
    {
        $this->holidaysStart = strtotime($config['holidays_start']);
        $this->holidaysEnd = strtotime($config['holidays_end']);
        $this->shipsUntil = strtotime($config['ships_until']);
        $this->notShipping = $config['not_shipping'];
        $this->holidays = $this->holidaysStart && $this->holidaysEnd;
        $this->shippingDate = strtotime('today');
        $this->shipToday = $this->isValidShippingDate() && !$this->isHolidayDate() &&
            date(self::TIME_FORMAT) < date(self::TIME_FORMAT, $this->shipsUntil);
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

    public function onFrontendListing(Enlight_Event_EventArgs $args): void
    {
        if (!$this->shipsUntil) {
            return;
        }
        /** @var Enlight_Controller_Action $subject */
        $subject = $args->getSubject();
        $article = $subject->View()->getAssign('sArticle');
        $this->shippingIn = $article['shipping_in'] ?? 1;
        if (!$this->shipToday) {
            ++$this->shippingIn;
        }
        if ($this->holidays) {
            $this->setShippingDateWithHolidays();
        } else {
            while ($this->shippingIn) {
                $this->addDayToDate();
                if ($this->isValidShippingDate()) {
                    --$this->shippingIn;
                }
            }
        }
//     dd(date(self::DATE_FORMAT, $this->shippingDate));
    }

    private function setShippingDateWithHolidays(): void
    {
        while ($this->shippingIn) {
            $this->addDayToDate();
            switch (true) {
                case $this->shippingIn === 1 and
                    $this->shippingDate === $this->holidaysStart and
                    $this->isValidShippingDate():
                    break 2;
                case $this->isHolidayDate() and !$this->shipToday:
                    $this->addDayToDate($this->holidaysEnd);
                    break;
                case !$this->isHolidayDate() and $this->isValidShippingDate():
                    --$this->shippingIn;
            }
        }
    }

    /**
     * If parameter $date is not null, set current shippingDate  to the given $date value + 1 day
     * @param null $date
     */
    private function addDayToDate($date = null): void
    {
        $this->shippingDate = strtotime('+1 day', $date ?? $this->shippingDate);
    }

    /**
     * @return bool
     */
    private function isValidShippingDate(): bool
    {
        return !in_array(date('D', $this->shippingDate), $this->notShipping, true);
    }

    /**
     * @return bool
     */
    private function isHolidayDate(): bool
    {
        return $this->shippingDate >= $this->holidaysStart && $this->shippingDate <= $this->holidaysEnd;
    }
}
