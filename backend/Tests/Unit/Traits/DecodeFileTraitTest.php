<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Tests\Unit\Traits;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Traits\DecodeFileTrait;
use Silex\WebTestCase;
use Symfony\Component\Finder\Finder;

class DecodeFileTraitTest extends WebTestCase
{
    /**
     * Test DecodeFileTrait exists.
     */
    public function testDecodeFileTraitExists(): void
    {
        $this->assertTrue(trait_exists(DecodeFileTrait::class));
    }

    /**
     * Test loadFiles result is instanceof class Finder.
     */
    public function testFinderClassLoadFiles(): void
    {
        /** @var BackendApplication $app */
        $app = $this->createApplication();
        $this->assertInstanceOf(Finder::class, $app->loadFiles('json', 'data'));
    }

    /**
     * Test loadFiles empty.
     */
    public function testEmptyLoadFiles(): void
    {
        /** @var BackendApplication $app */
        $app = $this->createApplication();
        $finder = $app->loadFiles('*.xml', 'data');
        $this->assertEquals(0, $finder->count());
    }

    /**
     * Test decodeFiles function throwing UnsupportedException.
     */
    public function testFilterDecodeFiles(): void
    {
        /** @var BackendApplication $app */
        $app = $this->createApplication();
        $this->assertNull($app->decodeFiles('*.yaml', 'data')->getReturn());
    }

    /**
     * Test decodeFiles function.
     */
    public function testDecodeFiles(): void
    {
        /** @var BackendApplication $app */
        $app = $this->createApplication();
        $this->assertCount(15, iterator_to_array($app->decodeFiles()));
    }

    /**
     * @return BackendApplication
     */
    public function createApplication()
    {
        if (!\defined('ROOT_PATH')) {
            \define('ROOT_PATH', realpath('.'));
        }

        return require __DIR__.'/../../../src/Client/Webapp/app.php';
    }
}
