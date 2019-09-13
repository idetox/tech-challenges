<?php

namespace IWD\JOBINTERVIEW\Provider;


use IWD\JOBINTERVIEW\Normalizer\DateNormalizer;
use IWD\JOBINTERVIEW\Normalizer\StudyNormalizer;
use IWD\JOBINTERVIEW\Normalizer\SurveyDenormalizer;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class SurveysServiceProvider implements ServiceProviderInterface
{

  /**
   * {@inheritdoc}
   *
   * @param Container $app A container instance
   */
  public function register(Container $app)
  {
    $app['serializer'] = function ($app) {
      return new Serializer($app['serializer.normalizers'], $app['serializer.encoders']);
    };

    $app['serializer.encoders'] = function () {
      return [new JsonEncoder(), new XmlEncoder()];
    };

    $app['serializer.normalizers'] = function () {
      return [new StudyNormalizer(), new DateNormalizer(), new GetSetMethodNormalizer(), new DateTimeNormalizer()];
    };

    $app['answer_service'] = function() {
      return new AnswerService();
    };
  }
}