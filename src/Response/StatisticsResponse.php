<?php
namespace Dashboard\Response;

class StatisticsResponse
{

    /** @var int  */
    public int $totalCountCustomers;

    /** @var int  */
    public int $totalCountOrders;

    /** @var float  */
    public float $totalRevenue;

    /** @var StatisticsPerDayResponse[]  */
    public array $statisticsPerDayResponses;

    /**
     * @param int $totalCountCustomers
     * @param int $totalCountOrders
     * @param float $totalRevenue
     * @param array $statisticsPerDayResponses
     * @return StatisticsResponse
     */
    public static function createModel(int $totalCountCustomers, int $totalCountOrders, float $totalRevenue, array $statisticsPerDayResponses): StatisticsResponse
    {
        $obj = new self();
        $obj->totalCountCustomers = $totalCountCustomers;
        $obj->totalCountOrders = $totalCountOrders;
        $obj->totalRevenue = $totalRevenue;
        $obj->statisticsPerDayResponses = $statisticsPerDayResponses;

        return $obj;
    }
}