<?php

use Dashboard\Controllers\DashboardController;
use Dashboard\Datamodels\OrderCountPerDay;
use Dashboard\Response\StatisticsResponse;
use Dashboard\Service\StatisticsService;

class DashboardControllerTest extends AbstractTest {

    /** @var StatisticsService  */
    private StatisticsService $statisticsService;

    /** @var DashboardController  */
    private DashboardController $dashboardController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->statisticsService = $this->createStandardServiceMock('Dashboard\Service\StatisticsService', array(
            'Dashboard\Dao\CustomerDao',
            'Dashboard\Dao\OrderDao',
            'Dashboard\Dao\OrderItemDao'
        ));
        $this->dashboardController = new DashboardController($this->statisticsService);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDates()
    {
        $fromDate = '2012-01-01';
        $toDate = '2013-01-01';

        $statisticsResponses = array(
            StatisticsResponse::createModel(
                OrderCountPerDay::withDbRow(array(
                    "purchase_date" => "2012-02-02", "order_count" => 1)
                )
            ),
            StatisticsResponse::createModel(
                OrderCountPerDay::withDbRow(array(
                        "purchase_date" => "2012-02-04", "order_count" => 2)
                )
            ),
            StatisticsResponse::createModel(
                OrderCountPerDay::withDbRow(array(
                        "purchase_date" => "2012-02-06", "order_count" => 3)
                )
            )
        );

        $this->statisticsService->method('getStatisticsBetweenDates')->willReturn($statisticsResponses);
        $this->statisticsService->expects($this->once())
            ->method('getStatisticsBetweenDates')
            ->with($fromDate, $toDate);

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($statisticsResponses, $result);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesNoResult()
    {
        $fromDate = '2012-01-01';
        $toDate = '2013-01-01';

        $statisticsResponses = array();

        $this->statisticsService->method('getStatisticsBetweenDates')->willReturn($statisticsResponses);
        $this->statisticsService->expects($this->once())
            ->method('getStatisticsBetweenDates')
            ->with($fromDate, $toDate);

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($statisticsResponses, $result);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesFromDateGreaterThanToDate()
    {
        $fromDate = '2014-01-01';
        $toDate = '2013-01-01';

        $expectedResponse = array("errorMessage" => "From date greater than to date");

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesInvalidFromDate()
    {
        $fromDate = '20-01-01';
        $toDate = '2013-01-01';

        $expectedResponse = array("errorMessage" => "Failed to validate from date, expected format (YYYY-MM-DD)");

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesInvalidToDate()
    {
        $fromDate = '2011-01-01';
        $toDate = '2013-22-01';

        $expectedResponse = array("errorMessage" => "Failed to validate to date, expected format (YYYY-MM-DD)");

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * @covers DashboardController::getStatisticsBetweenDates
     * @throws Exception
     */
    public function testGetStatisticsBetweenDatesNoFromDate()
    {
        $fromDate = '';
        $toDate = '2022-22-01';

        $expectedResponse = array("errorMessage" => "From date missing. Failed to validate from date, expected format (YYYY-MM-DD). Failed to validate to date, expected format (YYYY-MM-DD)");

        $result = $this->dashboardController->getStatisticsBetweenDates($fromDate, $toDate);

        $this->assertEquals($expectedResponse, $result);
    }

}