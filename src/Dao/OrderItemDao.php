<?php
namespace Dashboard\Dao;

use Dashboard\Database\DbStatementHelper;
use Dashboard\Datamodels\RevenuePerDay;

class OrderItemDao extends AbstractDao
{
    /**
     * Gets sum of total revenues for a purchase date
     *
     * @param string $fromDate
     * @param string $toDate
     * @return RevenuePerDay[]
     * @throws \Exception
     */
    public function getTotalRevenueCountPerDayBetweenDates(string $fromDate, string $toDate): array
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT order.purchase_date, SUM(order_item.price * order_item.quantity) " .
                 "as total_revenue " .
                 "FROM order_item " .
                 "INNER JOIN `order` ON order_item.order_id = order.id " .
                 "WHERE (DATE( order.purchase_date) BETWEEN ? AND ?) " .
                 "GROUP BY order.purchase_date;";
        $rows = $helper->selectAllByParams($query, array($fromDate, $toDate));

        $revenuesPerDay = array();
        foreach ($rows as $row) {
            $revenuesPerDay[] = RevenuePerDay::withDbRow($row);
        }

        return $revenuesPerDay;
    }
}