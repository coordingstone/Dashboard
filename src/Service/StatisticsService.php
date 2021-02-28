<?php
namespace Dashboard\Service;

use Dashboard\Dao\CustomerDao;
use Dashboard\Dao\OrderDao;
use Dashboard\Dao\OrderItemDao;
use Dashboard\Response\StatisticsPerDayResponse;
use Dashboard\Response\StatisticsResponse;

class StatisticsService
{

    /**
     * @var CustomerDao
     */
    private CustomerDao $customerDao;

    /**
     * @var OrderDao
     */
    private OrderDao $orderDao;

    /**
     * @var OrderItemDao
     */
    private OrderItemDao $orderItemDao;

    public function __construct(CustomerDao $customerDao, OrderDao $orderDao, OrderItemDao $orderItemDao)
    {
        $this->customerDao = $customerDao;
        $this->orderDao = $orderDao;
        $this->orderItemDao = $orderItemDao;
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return StatisticsResponse
     * @throws \Exception
     */
    public function getStatisticsBetweenDates(string $fromDate, string $toDate): StatisticsResponse
    {
        $totalRevenuesPerDay = $this->orderItemDao->getTotalRevenueCountPerDayBetweenDates($fromDate, $toDate);
        $orders = $this->orderDao->getOrderCountPerDayBetweenDates($fromDate, $toDate);
        $customers = $this->customerDao->getCustomerCountPerDayBetweenDates($fromDate, $toDate);

        $statisticsResponses = array();
        foreach ($orders as $order) {
            $statisticsResponses[] = StatisticsPerDayResponse::createModel($order);
        }

        $totalCustomerCount = 0;
        $totalRevenue = 0;
        $totalOrderCount = 0;
        foreach ($statisticsResponses as $statisticsResponse) {
            $totalOrderCount += $statisticsResponse->orderCount;
            foreach ($totalRevenuesPerDay as $totalRevenuePerDay) {
                if ($totalRevenuePerDay->purchaseDate === $statisticsResponse->purchaseDate) {
                    $statisticsResponse->totalRevenue = $totalRevenuePerDay->totalRevenue;
                    $totalRevenue += $totalRevenuePerDay->totalRevenue;
                }
            }
            foreach ($customers as $customer) {
                if ($customer->purchaseDate === $statisticsResponse->purchaseDate) {
                    $statisticsResponse->customerCount = $customer->customerCount;
                    $totalCustomerCount += $customer->customerCount;
                }
            }
        }

        $totalRevenue = number_format($totalRevenue, 2, ".", "");

        return  StatisticsResponse::createModel($totalCustomerCount, $totalOrderCount, $totalRevenue, $statisticsResponses);

    }

}