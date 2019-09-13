<?php

namespace IWD\JOBINTERVIEW\Service;

use IWD\JOBINTERVIEW\Exception\FieldNotFoundException;

class AnswerService
{

  public $qcm;
  public $numeric;
  public $date;

  public function __construct()
  {
    $this->qcm = $this->initField('qcm');
    $this->numeric = $this->initField('numeric');
    $this->date = $this->initField('date');
  }

  /**
   * Instantiate fields
   * @throws FieldNotFoundException
   */
  private function initField(string $type): array
  {
    switch ($type) {
      case 'numeric':
        return [
          'label' => '',
          'answer' => 0,
          'count' => 0
        ];
        break;
      case 'qcm':
      case 'date':
        return [
          'label' => '',
          'answer' => []
        ];
        break;
      default:
        throw new FieldNotFoundException();
    }
  }

  /**
   *
   */
  public function aggregateQuestions(array $questions)
  {
    foreach ($questions as $question) {
      $this->aggregateQuestion($question);
    }
    return [
      'qcm' => $this->qcm,
      'numeric' => $this->numeric,
      'date' => $this->date,
    ];
  }

  public function countAnswers($question)
  {
    $options = $question['options'];
    $this->qcm['label'] = $question['label'];
    array_walk($question['answer'], function ($item, $key) use ($options) {
      isset($this->qcm['answer'][$options[$key]]) ? ($this->qcm['answer'][$options[$key]] += $item ? 1 : 0)
        : $this->qcm['answer'][$options[$key]] = $item ? 1 : 0;
    });
  }

  public function countNumerics($question)
  {
    $this->numeric['label'] = $question['label'];
    $this->numeric['answer'] += $question['answer'];
    $this->numeric['count']++;
  }

  public function saveDates($question)
  {
    $this->date['label'] = $question['label'];
    $this->date['answer'][] = $question['answer'];
  }




  public function aggregateQuestion(array $question)
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