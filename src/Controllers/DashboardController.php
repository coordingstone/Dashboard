<?php
namespace Dashboard\Controllers;

use Dashboard\Service\StatisticsService;
use DateTime;

class DashboardController extends AbstractController
{
    /** @var StatisticsService $statisticsService */
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $dashboardService)
    {
        $this->statisticsService = $dashboardService;
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return array|object
     * @throws \Exception
     */
    public function getStatisticsBetweenDates(string $fromDate, string $toDate)
    {

        $fromDate = filter_var($fromDate, FILTER_SANITIZE_STRING);
        $toDate = filter_var($toDate, FILTER_SANITIZE_STRING);

        $errorMessage = $this->validateDates($fromDate, $toDate);
        if (!empty($errorMessage)) {
            $error = array("errorMessage" => $errorMessage);
            return $this->generateError(400, $error);
        }

        $data = $this->statisticsService->getStatisticsBetweenDates($fromDate, $toDate);

        return $this->generateResponse(200, $data);

    }


    /**
     * @param string $fromDate
     * @param string $toDate
     * @return string
     */
    private function validateDates(string $fromDate, string $toDate)
    {
        $errorMessage = array();

        if (empty($fromDate)) {
            $errorMessage[] = 'From date missing';
        }

        if (empty($toDate)) {
            $errorMessage[] = 'To date missing';
        }

        if (!$this->validateDate($fromDate)) {
            $errorMessage[] = 'Failed to validate from date, expected format (YYYY-MM-DD)';
        }

        if (!$this->validateDate($toDate)) {
            $errorMessage[] = 'Failed to validate to date, expected format (YYYY-MM-DD)';
        }

        if ($this->fromDateGreaterThanToDate($fromDate, $toDate)) {
            $errorMessage[] = 'From date greater than to date';
        }

        return implode('. ', $errorMessage);
    }

    /**
     * @param string $date
     * @return bool
     */
    private function validateDate(string $date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return bool
     */
    private function fromDateGreaterThanToDate(string $fromDate, string $toDate): bool
    {
        $from = DateTime::createFromFormat('Y-m-d', $fromDate);
        $to = DateTime::createFromFormat('Y-m-d', $toDate);

        if ($from > $to) {
            return true;
        }

        return false;

    }
}