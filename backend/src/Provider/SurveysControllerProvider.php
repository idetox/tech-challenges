<?php

declare(strict_types=1);

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

        // Dependency injection for controllers
        $app['list_surveys.controller'] = function () use ($app) {
            return new ListSurveys($app);
        };
        $app['show_surveys.controller'] = function () use ($app) {
            return new ShowSurveys($app, $app['request_stack']->getCurrentRequest());
        };
        $app['list_answers.controller'] = function () use ($app) {
            return new ListAnswers($app, $app['request_stack']->getCurrentRequest(), $app['answer_service']);
        };
        $app['show_answers.controller'] = function () use ($app) {
            return new ShowAnswers($app, $app['request_stack']->getCurrentRequest(), $app['answer_service']);
        };

        // Routes to show surveys
        $controllers->get('surveys', 'list_surveys.controller:__invoke')
            ->bind('list_surveys');
        $controllers->get('surveys/{code}', 'show_surveys.controller:__invoke')
            ->bind('show_surveys');

        // Routes to show answers by survey code
        $controllers->get('surveys/{code}/answers', 'list_answers.controller:__invoke')
            ->bind('list_answers');
        $controllers->get('surveys/{code}/answers/{type}', 'show_answers.controller:__invoke')
            ->bind('show_answers');

        return $controllers;
    }
}
