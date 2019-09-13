<?php

namespace IWD\JOBINTERVIEW\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShowAnswers
{
  private $request;
  private $app;
  private $qcm;
  private $numeric;
  private $date;
  private $survey;

  public function __construct(Application $app, Request $request)
  {
    $this->request = $request;
    $this->app = $app;
    $this->qcm = $this->initQCM();
    $this->numeric = $this->initNumeric();
    $this->date = $this->initDate();
    $this->survey = [];
  }

  public function __invoke()
  {
    $code = $this->request->get('code');
    $type = $this->request->get('type');
    foreach ($this->app->decodeFiles() as $decodeFile) {
      if ($this->skip($decodeFile, $code)) {
        continue;
      }
      $this->survey = $decodeFile['survey'];
      $this->aggregateQuestions($decodeFile['questions'], $type);
    }
    return new JsonResponse($this->renderQuestions($type));
  }

  private function renderQuestions($type = null)
  {
    if (null === $type || 'qcm' === $type) {
      $result['qcm'] = $this->qcm;
    }
    if (null === $type || 'numeric' === $type) {
      // Get average
      $this->numeric['answer'] = (float)$this->numeric['answer'] / $this->numeric['count'];
      unset($this->numeric['count']);

      $result['numeric'] = $this->numeric;
    }
    if (null === $type || 'date' === $type) {
      // Get unique date & sort them by desc
      $this->date['answer'] = array_unique($this->date['answer']);
      rsort($this->date['answer']);

      $result['date'] = $this->date;
    }
    return $result ?? [];
  }

  private function skip(array $data, string $code)
  {
    return $code !== $data['survey']['code'];
  }

  private function skipQuestion(array $data, string $type)
  {
    return $type !== $data['type'];
  }

  private function aggregateQuestions(array $questions, $type = null)
  {
    foreach ($questions as $question) {
      if ($type && $this->skipQuestion($question, $type)) {
        continue;
      }
      $this->aggregateQuestion($question);
    }
  }

  private function countAnswers($question)
  {
    $options = $question['options'];
    $this->qcm['label'] = $question['label'];
    array_walk($question['answer'], function ($item, $key) use ($options) {
      isset($this->qcm['answer'][$options[$key]]) ? ($this->qcm['answer'][$options[$key]] += $item ? 1 : 0)
        : $this->qcm['answer'][$options[$key]] = $item ? 1 : 0;
    });
  }

  private function countNumerics($question)
  {
    $this->numeric['label'] = $question['label'];
    $this->numeric['answer'] += $question['answer'];
    $this->numeric['count']++;
  }

  private function saveDates($question)
  {
    $this->date['label'] = $question['label'];
    $this->date['answer'][] = $question['answer'];
  }

  private function initQCM(): array
  {
    $qcm['label'] = '';
    $qcm['answer'] = [];
    return $qcm;
  }

  private function initNumeric(): array
  {
    $numeric['label'] = '';
    $numeric['answer'] = 0;
    $numeric['count'] = 0;
    return $numeric;
  }

  private function initDate(): array
  {
    $date['label'] = '';
    $date['answer'] = [];
    return $date;
  }


  private function aggregateQuestion(array $question)
  {
    switch (strtolower($question['type'])) {
      case 'qcm':
        $this->countAnswers($question);
        break;
      case 'numeric':
        $this->countNumerics($question);
        break;
      case 'date':
        $this->saveDates($question);
        break;
    }
  }
}