<?php
namespace Dashboard\Dao;

use Dashboard\Database\DbStatementHelper;
use Dashboard\Datamodels\OrderCountPerDay;

class OrderDao extends AbstractDao
{
    /**
     * Gets order count for a purchase date
     *
     * @param string $fromDate
     * @param string $toDate
     * @return OrderCountPerDay[]
     * @throws \Exception
     */
    public function getOrderCountPerDayBetweenDates(string $fromDate, string $toDate): array
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT order.purchase_date, COUNT(order.id) as order_count " .
                 "FROM `order` " .
                 "WHERE (DATE( order.purchase_date) BETWEEN ? AND ?) " .
                 "GROUP BY order.purchase_date " .
                 "ORDER BY order.purchase_date";
        $rows = $helper->selectAllByParams($query, array($fromDate, $toDate));
        $orders = array();

        foreach ($rows as $row) {
            $orders[] = OrderCountPerDay::withDbRow($row);
        }

        return $orders;
    }
}