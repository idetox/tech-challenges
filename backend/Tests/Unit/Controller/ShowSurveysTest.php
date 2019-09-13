<?php

namespace IWD\JOBINTERVIEW\Tests\Unit\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Controller\ShowSurveys;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ShowSurveysTest extends WebTestCase
{
    public function testFalseSkip(): void
    {
        $app = $this->createApplication();
        $request = new Request();
        $showSurvey = new ShowSurveys($app,$request);
        $this->assertFalse($showSurvey->skip(['survey' => ['code' => 'XX1']], 'XX1'));
    }

    public function testTrueSkip(): void
    {
        $app = $this->createApplication();
        $request = new Request();
        $showSurvey = new ShowSurveys($app,$request);
        $this->assertTrue($showSurvey->skip(['survey' => ['code' => 'XX1']], 'XX2'));
    }

    public function createApplication(): BackendApplication
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', realpath('.'));
        }
        return require __DIR__ . '/../../../src/Client/Webapp/app.php';
    }
}
