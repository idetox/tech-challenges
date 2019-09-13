<?php

declare(strict_types=1);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath('.'));
}

if (false === file_exists(ROOT_PATH.'/vendor/autoload.php')) {
    echo 'run this command first: composer install';
    exit();
}
require_once ROOT_PATH.'/vendor/autoload.php';

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Provider\SurveysControllerProvider;
use IWD\JOBINTERVIEW\Provider\SurveysServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new BackendApplication();

$app->register(new ServiceControllerServiceProvider());
$app->register(new SurveysServiceProvider());

$app->after(function (Request $request, Response $response): void {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->get('/', function () {
    return new Response('Status OK');
});

$app->mount('/', new SurveysControllerProvider());

$app->run();

return $app;
