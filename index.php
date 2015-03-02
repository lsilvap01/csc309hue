<?php
require 'vendor/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->setName('SynergySpace');

$app->config(array(
    'debug' => true,
    'templates.path' => './templates'
));

$app->get('/', function () use ($app) {
    $app->render('home.php', array('appName' => $app->getName()));
});

$app->get('/login', function () use ($app) {
    $app->render('login.php', array('appName' => $app->getName()));
});

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});
$app->run();
?>