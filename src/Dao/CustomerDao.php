<?php
namespace Dashboard\Dao;

use Dashboard\Database\DbStatementHelper;
use Dashboard\Datamodels\CustomerCountPerDay;

class CustomerDao extends AbstractDao
{
    /**
     * @param string $fromDate
     * @param string $toDate
     * @return CustomerCountPerDay[]
     * @throws \Exception
     */
    public function getCustomerCountPerDayBetweenDates(string $fromDate, string $toDate): array
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT order.purchase_date, COUNT(DISTINCT customer.id) as customer_count " .
                 "FROM customer " .
                 "INNER JOIN `order` ON customer.id = order.customer_id " .
                 "WHERE (DATE( order.purchase_date) BETWEEN ? AND ?) " .
                 "GROUP BY order.purchase_date;";
        $rows = $helper->selectAllByParams($query, array($fromDate, $toDate));
        $customers = array();

        foreach ($rows as $row) {
            $customers[] = CustomerCountPerDay::withDbRow($row);
        }

        return $customers;
    }
}