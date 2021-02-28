<?php
namespace Dashboard\Response;

use Dashboard\Datamodels\OrderCountPerDay;

class StatisticsPerDayResponse
{
    /**
     * @var string
     */
    public string $purchaseDate;

    /**
     * @var int
     */
    public int $orderCount = 0;

    /**
     * @var float
     */
    public float $totalRevenue = 0;

    /**
     * @var int
     */
    public int $customerCount = 0;

    /**
     * @param OrderCountPerDay $orderCountPerDay
     * @return StatisticsPerDayResponse
     */
    public static function createModel(OrderCountPerDay $orderCountPerDay): StatisticsPerDayResponse
    {
        $obj = new self();
        $obj->purchaseDate = $orderCountPerDay->purchaseDate;
        $obj->orderCount = $orderCountPerDay->orderCount ? $orderCountPerDay->orderCount : 0;

        return $obj;
    }
}