<?php
namespace Dashboard\Dao;

use Dashboard\Database\Db;

abstract class AbstractDao
{
    /** @var Db */
    protected Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }
}