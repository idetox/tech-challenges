<?php

namespace IWD\JOBINTERVIEW\Tests\Functionnal;

use IWD\JOBINTERVIEW\BackendApplication;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Tests\JsonResponseTest;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ApplicationFailuresFunctionalTest extends WebTestCase
{
    /**
     * Check return code when wrong code passed to url
     */
    public function testWrongCodeShowSurvey()
    {
        $client = $this->createClient();
        $client->request('GET', '/surveys/XXX');
        $this->assertSame(JsonResponse::HTTP_NOT_FOUND,$client->getResponse()->getStatusCode());
    }

    /**
     * Check return code when wrong code passed to url
     */
    public function testWrongCodeListAnswer()
    {
        $client = $this->createClient();
        $client->request('GET', '/surveys/XXX/answers');
        $this->assertSame(JsonResponse::HTTP_NOT_FOUND,$client->getResponse()->getStatusCode());
    }

    /**
     * Check return code when wrong type passed to url
     */
    public function testWrongTypeShowAnswer()
    {
        $client = $this->createClient();
        $client->request('GET', '/surveys/XX1/answers/test');
        $this->assertSame(JsonResponse::HTTP_UNPROCESSABLE_ENTITY,$client->getResponse()->getStatusCode());
    }

    /**
     * Creates the application.
     *
     * @return BackendApplication
     */
    public function createApplication()
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', realpath('.'));
        }
        return require __DIR__ . '/../../src/Client/Webapp/app.php';
    }
}
