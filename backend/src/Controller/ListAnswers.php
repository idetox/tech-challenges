<?php

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListAnswers extends AnswerAbstractController
{
  private $request;
  /** @var BackendApplication  */
  private $app;
  private $answerService;

  public function __construct(Application $app, Request $request, AnswerService $answerService)
  {
    $this->request = $request;
    $this->app = $app;
    $this->answerService = $answerService;
  }

  public function __invoke()
  {
    $code = $this->request->get('code');
    foreach ($this->app->decodeFiles() as $decodeFile) {
      if ($this->skip($decodeFile, $code)) {
        continue;
      }
      $this->survey = $decodeFile['survey'];
      $data = $this->answerService->aggregateQuestions($decodeFile['questions']);
    }
    return new JsonResponse($this->renderQuestions($data ?? []));
  }

  public function renderQuestions(array $data): array
  {
    if(empty($data)) {
      return [];
    }

    // Get average & remove total count
    $data['numeric']['answer'] = 0 === $data['numeric']['count'] ? 0 : (float)$data['numeric']['answer'] / $data['numeric']['count'];
    unset($data['numeric']['count']);

    // Get unique date & sort them by desc
    $data['date']['answer'] = array_unique($data['date']['answer']);
    rsort($data['date']['answer']);

    return $data;
  }

  public function skip(array $data, string $code)
  {
    return $code !== $data['survey']['code'];
  }
}