<?php
namespace Dashboard\Datamodels;

use Dashboard\Interfaces\DatamodelInterface;

class CustomerCountPerDay implements DatamodelInterface
{
    /**
     * @var string
     */
    public string $purchaseDate;

    /**
     * @var int
     */
    public int $customerCount;

    /**
     * @param array $row
     * @return CustomerCountPerDay
     */
    public static function withDbRow(array $row): CustomerCountPerDay
    {
        $obj = new self();
        $obj->purchaseDate = $row['purchase_date'];
        $obj->customerCount = $row['customer_count'];

        return $obj;
    }
}