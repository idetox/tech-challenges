<?php

namespace IWD\JOBINTERVIEW\Tests\Unit\Controller;

use IWD\JOBINTERVIEW\Controller\ListSurveys;
use Silex\WebTestCase;

class ListSurveyTest extends WebTestCase
{
  public function testSkip()
  {
    $listSurvey = new ListSurveys($this->createApplication());
    $this->assertNotTrue($listSurvey->skip(['survey'=>['code'=>'XX1']],[]));
  }

  public function createApplication()
  {
    if(!defined('ROOT_PATH')) {
      define('ROOT_PATH', realpath('.'));
    }
    return require __DIR__.'/../../../src/Client/Webapp/app.php';
  }
}