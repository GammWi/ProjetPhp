<?php
/**
 * File:  index.php
 * Creation Date: 04/12/2017
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/hello/world', function () {
    echo "Hello, World !";
});

$app->run();