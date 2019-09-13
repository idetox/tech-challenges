<?php

namespace IWD\JOBINTERVIEW\Provider;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Controller\ListAnswers;
use IWD\JOBINTERVIEW\Controller\ListSurveys;
use IWD\JOBINTERVIEW\Controller\ShowAnswers;
use IWD\JOBINTERVIEW\Controller\ShowSurveys;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class SurveysControllerProvider implements ControllerProviderInterface
{

  /**
   * Returns routes to connect to the given application.
   *
   * @param BackendApplication $app An Application instance
   *
   * @return ControllerCollection A ControllerCollection instance
   */
  public function connect(Application $app)
  {
    $controllers = $app['controllers_factory'];
    $app['list_surveys.controller'] = function() use ($app) {
      return new ListSurveys($app);
    };
    $app['show_surveys.controller'] = function() use($app) {
      return new ShowSurveys($app,$app['request_stack']->getCurrentRequest());
    };
    $app['list_answers.controller'] = function() use($app) {
      return new ListAnswers($app,$app['request_stack']->getCurrentRequest(), $app['answer_service']);
    };
    $app['show_answers.controller'] = function() use($app) {
      return new ShowAnswers($app,$app['request_stack']->getCurrentRequest(), $app['answer_service']);
    };

    $controllers->get('/','list_surveys.controller:__invoke');
    $controllers->get('/{code}', 'show_surveys.controller:__invoke');

    $controllers->get('/{code}/answers', 'list_answers.controller:__invoke');
    $controllers->get('/{code}/answers/{type}', 'show_answers.controller:__invoke');

    return $controllers;
  }
}