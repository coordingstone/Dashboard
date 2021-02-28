<?php

use DI\Container;

require_once('vendor/autoload.php');

$config = include('config.php');
$builder = new DI\ContainerBuilder();
$builder->addDefinitions(array(
    'PDO' => function (Container $c) use ($config) {
        return new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbName'] . ";port=" . $config['port'], $config['user'], $config['password']);
    }
));

try {
    $container = $builder->build();
} catch (Exception $e) {
    http_response_code(500);
    return json_encode("Something went wrong");
}
