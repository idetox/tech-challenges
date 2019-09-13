<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Tests\Functionnal;

use IWD\JOBINTERVIEW\BackendApplication;
use Silex\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * Check all pages return 200.
     *
     * @dataProvider urlProvider
     */
    public function testPagesAreSuccessful($url): void
    {
        $client = $this->createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isOk());
    }

    /**
     * Check all pages return json.
     *
     * @dataProvider urlProvider
     */
    public function testPagesReturnJson($url): void
    {
        $client = $this->createClient();
        $client->request('GET', $url);
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));
    }

    public function urlProvider()
    {
        yield ['/surveys'];
        yield ['/surveys/XX1'];
        yield ['/surveys/XX1/answers'];
        yield ['/surveys/XX1/answers/qcm'];
    }

    /**
     * Creates the application.
     *
     * @return BackendApplication
     */
    public function createApplication()
    {
        if (!\defined('ROOT_PATH')) {
            \define('ROOT_PATH', realpath('.'));
        }

        return require __DIR__.'/../../src/Client/Webapp/app.php';
    }
}
