<?php

require_once('../bootstrap.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

/** @var \Dashboard\Controllers\DashboardController $dashboardController */
try {
    $dashboardController = $container->get('Dashboard\Controllers\DashboardController');
} catch (Exception $e) {
    http_response_code(500);
    echo "Something went wrong";
}

switch ($method) {
    case 'GET':
        try {
            echo json_encode($dashboardController->getStatisticsBetweenDates($_GET['fromDate'], $_GET['toDate']));
        } catch (Exception $e) {
            http_response_code(500);
            echo "Something went wrong";
        }
        break;
    default:
        http_response_code(405);
        break;
}