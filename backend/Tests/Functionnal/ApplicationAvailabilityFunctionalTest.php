<?php

namespace IWD\JOBINTERVIEW\Tests\Functionnal;

use IWD\JOBINTERVIEW\BackendApplication;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{

  /**
   * @dataProvider urlProvider
   * @group Functional
   */
  public function testPageAreSuccessful($url)
  {
    $client = $this->createClient();
    $client->request('GET', $url);
    $this->assertTrue($client->getResponse()->isOk());
  }

  public function urlProvider()
  {
    yield ['/surveys/'];
    yield ['/surveys/XX1'];
//    yield ['/surveys/XX1/answers'];
  }

  /**
   * Creates the application.
   *
   * @return BackendApplication
   */
  public function createApplication()
  {
    if(!defined('ROOT_PATH')) {
      define('ROOT_PATH', realpath('.'));
    }
    return require __DIR__.'/../../src/Client/Webapp/app.php';
  }
}