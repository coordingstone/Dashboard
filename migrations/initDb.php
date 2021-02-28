<?php

require_once('../bootstrap.php');

try {
    /** @var \Dashboard\Database\Db $db */
    $db = $container->get('Dashboard\Database\Db');
    $sql = file_get_contents('sql/init.sql');
    $result = $db->getConnection()->exec($sql);
    echo "Created db tables" . PHP_EOL;
} catch (Exception $exception) {
    echo "Something went wrong while creating database {$exception->getMessage()}" . PHP_EOL;
}