<?php

use Dashboard\Datamodels\CustomerCountPerDay;
use Dashboard\Datamodels\OrderCountPerDay;
use Dashboard\Datamodels\RevenuePerDay;
use Dashboard\Response\StatisticsResponse;
use Dashboard\Service\StatisticsService;
use PHPUnit\Framework\MockObject\MockObject;

class StatisticsServiceTest extends AbstractTest
{

    /** @var MockObject $orderDaoMock */
    private MockObject $orderDaoMock;

    /** @var MockObject $orderItemDaoMock */
    private MockObject $orderItemDaoMock;

    /** @var MockObject $customerDaoMock */
    private MockObject $customerDaoMock;

    /** @var StatisticsService $statisticsService */
    private StatisticsService $statisticsService;


    protected function setUp(): void
    {
        parent::setUp();

        $this->orderDaoMock = $this->createStandardDaoMock('Dashboard\Dao\OrderDao');
        $this->orderItemDaoMock = $this->createStandardDaoMock('Dashboard\Dao\OrderItemDao');
        $this->customerDaoMock = $this->createStandardDaoMock('Dashboard\Dao\CustomerDao');

        $this->statisticsService = new StatisticsService($this->customerDaoMock, $this->orderDaoMock, $this->orderItemDaoMock);

    }

    /**
     * @covers StatisticsService::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDates()
    {
        $revenuePerDay = RevenuePerDay::withDbRow(["purchase_date" => "2021-01-01", "total_revenue" => 3.0]);
        $customerCountPerDay = CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "customer_count" => 2]);
        $orderCountPerDay = OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "order_count" => 2]);

        $expectedResponse = StatisticsResponse::createModel($orderCountPerDay);
        $expectedResponse->totalRevenue = $revenuePerDay->totalRevenue;
        $expectedResponse->customerCount = $customerCountPerDay->customerCount;

        $this->orderItemDaoMock->method('getTotalRevenueCountPerDayBetweenDates')->willReturn(array($revenuePerDay));
        $this->orderItemDaoMock->expects($this->once())
            ->method('getTotalRevenueCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->orderDaoMock->method('getOrderCountPerDayBetweenDates')->willReturn(array($orderCountPerDay));
        $this->orderDaoMock->expects($this->once())
            ->method('getOrderCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->customerDaoMock->method('getCustomerCountPerDayBetweenDates')->willReturn(array($customerCountPerDay));
        $this->customerDaoMock->expects($this->once())
            ->method('getCustomerCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $result = $this->statisticsService->getStatisticsBetweenDates("2019-01-01", "2020-01-01");


        $this->assertIsArray($result);
        $this->assertEquals(array($expectedResponse), $result);
        $this->assertEquals(count($result), 1);
    }

    /**
     * @covers StatisticsService::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesNoResult()
    {
        $this->orderItemDaoMock->method('getTotalRevenueCountPerDayBetweenDates')->willReturn(array());
        $this->orderItemDaoMock->expects($this->once())
            ->method('getTotalRevenueCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->orderDaoMock->method('getOrderCountPerDayBetweenDates')->willReturn(array());
        $this->orderDaoMock->expects($this->once())
            ->method('getOrderCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->customerDaoMock->method('getCustomerCountPerDayBetweenDates')->willReturn(array());
        $this->customerDaoMock->expects($this->once())
            ->method('getCustomerCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $result = $this->statisticsService->getStatisticsBetweenDates("2019-01-01", "2020-01-01");

        $this->assertEquals(array(), $result);
    }

    /**
     * @covers StatisticsService::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesMultipleResults()
    {
        $revenuesPerDay = array(
            RevenuePerDay::withDbRow(["purchase_date" => "2021-01-01", "total_revenue" => 3.0]),
            RevenuePerDay::withDbRow(["purchase_date" => "2021-01-02", "total_revenue" => 5.0]),
            RevenuePerDay::withDbRow(["purchase_date" => "2021-01-03", "total_revenue" => 8.0])
        );

        $customerCountsPerDay = array(
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "customer_count" => 2]),
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-02",  "customer_count" => 1]),
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-03",  "customer_count" => 5])
        );

        $orderCountsPerDay = array(
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "order_count" => 2]),
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-02",  "order_count" => 5]),
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-03",  "order_count" => 8])
        );

        $expectedResponse = array();

        for ($i = 0; $i < count($orderCountsPerDay); $i++) {
            $statisticsResponse = StatisticsResponse::createModel($orderCountsPerDay[$i]);
            $statisticsResponse->totalRevenue = $revenuesPerDay[$i]->totalRevenue;
            $statisticsResponse->customerCount = $customerCountsPerDay[$i]->customerCount;

            $expectedResponse[] = $statisticsResponse;
        }


        $this->orderItemDaoMock->method('getTotalRevenueCountPerDayBetweenDates')->willReturn($revenuesPerDay);
        $this->orderItemDaoMock->expects($this->once())
            ->method('getTotalRevenueCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->orderDaoMock->method('getOrderCountPerDayBetweenDates')->willReturn($orderCountsPerDay);
        $this->orderDaoMock->expects($this->once())
            ->method('getOrderCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->customerDaoMock->method('getCustomerCountPerDayBetweenDates')->willReturn($customerCountsPerDay);
        $this->customerDaoMock->expects($this->once())
            ->method('getCustomerCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $result = $this->statisticsService->getStatisticsBetweenDates("2019-01-01", "2020-01-01");

        $this->assertIsArray($result);
        $this->assertEquals($expectedResponse, $result);
        $this->assertEquals(count($result), 3);
    }

    /**
     * @covers StatisticsService::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesNoOrderItem()
    {
        $customerCountsPerDay = array(
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "customer_count" => 2]),
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-02",  "customer_count" => 1]),
            CustomerCountPerDay::withDbRow(["purchase_date" => "2021-01-03",  "customer_count" => 5])
        );

        $orderCountsPerDay = array(
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-01",  "order_count" => 2]),
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-02",  "order_count" => 5]),
            OrderCountPerDay::withDbRow(["purchase_date" => "2021-01-03",  "order_count" => 8])
        );

        $expectedResponse = array();

        for ($i = 0; $i < count($orderCountsPerDay); $i++) {
            $statisticsResponse = StatisticsResponse::createModel($orderCountsPerDay[$i]);
            $statisticsResponse->customerCount = $customerCountsPerDay[$i]->customerCount;

            $expectedResponse[] = $statisticsResponse;
        }

        $this->orderItemDaoMock->method('getTotalRevenueCountPerDayBetweenDates')->willReturn(array());
        $this->orderItemDaoMock->expects($this->once())
            ->method('getTotalRevenueCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->orderDaoMock->method('getOrderCountPerDayBetweenDates')->willReturn($orderCountsPerDay);
        $this->orderDaoMock->expects($this->once())
            ->method('getOrderCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $this->customerDaoMock->method('getCustomerCountPerDayBetweenDates')->willReturn($customerCountsPerDay);
        $this->customerDaoMock->expects($this->once())
            ->method('getCustomerCountPerDayBetweenDates')
            ->with("2019-01-01", "2020-01-01");

        $result = $this->statisticsService->getStatisticsBetweenDates("2019-01-01", "2020-01-01");


        $this->assertIsArray($result);
        $this->assertEquals($expectedResponse, $result);
        $this->assertEquals(count($result), 3);
    }
}