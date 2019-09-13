<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Tests\Unit\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Controller\ListSurveys;
use Silex\WebTestCase;

class ListSurveysTest extends WebTestCase
{
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(ListSurveys::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testFalseSkip(): void
    {
        $app = $this->createApplication();
        $listSurvey = new ListSurveys($app);
        $this->assertFalse($listSurvey->skip(['survey' => ['code' => 'XX1']], []));
    }

    public function testTrueSkip(): void
    {
        $app = $this->createApplication();
        $listSurvey = new ListSurveys($app);
        $this->assertTrue($listSurvey->skip(['survey' => ['code' => 'XX1']], ['XX1']));
    }

    public function testSortSurvey(): void
    {
        $app = $this->createApplication();
        $listSurvey = new ListSurveys($app);
        $sortSurvey = self::getMethod('sortSurvey');
        $this->assertSame([['code' => 'XX1', 'name' => 'TEST 2'], ['code' => 'XX2', 'name' => 'TEST 1']], $sortSurvey->invokeArgs($listSurvey, [[['code' => 'XX2', 'name' => 'TEST 1'], ['code' => 'XX1', 'name' => 'TEST 2']]]));
    }

    public function createApplication(): BackendApplication
    {
        if (!\defined('ROOT_PATH')) {
            \define('ROOT_PATH', realpath('.'));
        }

        return require __DIR__.'/../../../src/Client/Webapp/app.php';
    }
}
