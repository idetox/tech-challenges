<?php
declare(strict_types=1);

if (file_exists(ROOT_PATH.'/vendor/autoload.php') === false) {
    echo "run this command first: composer install";
    exit();
}
require_once ROOT_PATH.'/vendor/autoload.php';

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Provider\SurveysControllerProvider;
use IWD\JOBINTERVIEW\Provider\SurveysServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new BackendApplication();

$app->register(new ServiceControllerServiceProvider());
$app->register(new SurveysServiceProvider());

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->get('/', function () use ($app) {
    return new Response('Status OK');
});

$app->mount('/surveys', new SurveysControllerProvider());

$app->run();

return $app;