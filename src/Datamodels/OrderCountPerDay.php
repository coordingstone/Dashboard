<?php
namespace Dashboard\Datamodels;

use Dashboard\Interfaces\DatamodelInterface;

class OrderCountPerDay implements DatamodelInterface
{
    /**
     * @var string
     */
    public string $purchaseDate;

    /**
     * @var int
     */
    public int $orderCount;

    /**
     * @param array $row
     * @return OrderCountPerDay
     */
    public static function withDbRow(array $row): OrderCountPerDay
    {
        $obj = new self();
        $obj->purchaseDate = $row['purchase_date'];
        $obj->orderCount = $row['order_count'];

        return $obj;
    }
}