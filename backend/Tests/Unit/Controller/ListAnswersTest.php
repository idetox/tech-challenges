<?php

namespace IWD\JOBINTERVIEW\Tests\Unit\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Controller\ListAnswers;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ListAnswersTest extends WebTestCase
{
    public function testFalseSkip(): void
    {
        $app = $this->createApplication();
        $request = new Request();
        $answerService = new AnswerService();
        $listAnswers = new ListAnswers($app,$request,$answerService);
        $this->assertFalse($listAnswers->skip(['survey' => ['code' => 'XX1']], 'XX1'));
    }

    public function testTrueSkip(): void
    {
        $app = $this->createApplication();
        $request = new Request();
        $answerService = new AnswerService();
        $listAnswers = new ListAnswers($app,$request,$answerService);
        $this->assertTrue($listAnswers->skip(['survey' => ['code' => 'XX1']], 'XX2'));
    }

    public function createApplication(): BackendApplication
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', realpath('.'));
        }
        return require __DIR__ . '/../../../src/Client/Webapp/app.php';
    }
}
