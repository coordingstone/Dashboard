<?php
namespace Dashboard\Datamodels;

use Dashboard\Interfaces\DatamodelInterface;

class RevenuePerDay implements DatamodelInterface
{
    /**
     * @var string
     */
    public string $purchaseDate;

    /**
     * @var float
     */
    public float $totalRevenue;

    /**
     * @param array $row
     * @return RevenuePerDay
     */
    public static function withDbRow(array $row): RevenuePerDay
    {
        $obj = new self();
        $obj->purchaseDate = $row['purchase_date'];
        $obj->totalRevenue = $row['total_revenue'];

        return $obj;
    }
}