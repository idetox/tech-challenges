<?php

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShowSurveys
{
  protected $app;
  private $request;

  public function __construct(BackendApplication $app, Request $request)
  {
    $this->app = $app;
    $this->request = $request;
  }
  public function __invoke()
  {
    $code = $this->request->get('code');
    foreach ($this->app->decodeFiles() as $decodeFile) {
      if ($this->skip($decodeFile, $code)) {
        continue;
      }
      $survey = $decodeFile['survey'];
      break;
    }

    return new JsonResponse($survey ?? []);
  }

  private function skip(array $data, string $code)
  {
    return $code !== $data['survey']['code'];
  }


}