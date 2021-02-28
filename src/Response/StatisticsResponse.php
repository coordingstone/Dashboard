<?php
namespace Dashboard\Response;

use Dashboard\Datamodels\OrderCountPerDay;

class StatisticsResponse
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
     * @return StatisticsResponse
     */
    public static function createModel(OrderCountPerDay $orderCountPerDay): StatisticsResponse
    {
        $obj = new self();
        $obj->purchaseDate = $orderCountPerDay->purchaseDate;
        $obj->orderCount = $orderCountPerDay->orderCount ? $orderCountPerDay->orderCount : 0;

        return $obj;
    }
}