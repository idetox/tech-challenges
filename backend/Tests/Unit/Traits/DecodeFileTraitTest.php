<?php

namespace IWD\JOBINTERVIEW\Tests\Unit\Traits;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Exception\UnsupportedFormatException;
use IWD\JOBINTERVIEW\Traits\DecodeFileTrait;
use Silex\WebTestCase;
use Symfony\Component\Finder\Finder;

class DecodeFileTraitTest extends WebTestCase
{
  /**
   * Test DecodeFileTrait exists
   */
  public function testDecodeFileTraitExists()
  {
    $this->assertTrue(trait_exists(DecodeFileTrait::class));
  }

  /**
   * Test loadFiles result is instanceof class Finder.
   */
  public function testFinderClassLoadFiles()
  {
    /** @var BackendApplication $app */
    $app = $this->createApplication();
    $this->assertInstanceOf(Finder::class, $app->loadFiles('json', 'data'));
  }

  /**
   * Test loadFiles empty.
   */
  public function testEmptyLoadFiles()
  {
    /** @var BackendApplication $app */
    $app = $this->createApplication();
    $finder = $app->loadFiles('*.xml', 'data');
    $this->assertEquals(0, $finder->count());
  }

  /**
   * Test decodeFiles function throwing UnsupportedException.
   */
  public function testUnsupportedExceptionDecodeFiles()
  {
    /** @var BackendApplication $app */
    $app = $this->createApplication();
    try{
      $app->decodeFiles('yaml', 'data')->getReturn();
    } catch (\Exception $e) {
      $this->assertInstanceOf(UnsupportedFormatException::class, $e);
    }
  }

  /**
   * Test decodeFiles function.
   */
  public function testDecodeFiles()
  {
    /** @var BackendApplication $app */
    $app = $this->createApplication();
    $this->assertCount(15, iterator_to_array($app->decodeFiles('json', 'data')));
  }

  /**
   * @return BackendApplication
   */
  public function createApplication()
  {
    if (!defined('ROOT_PATH')) {
      define('ROOT_PATH', realpath('.'));
    }
    return require __DIR__ . '/../../../src/Client/Webapp/app.php';
  }
}