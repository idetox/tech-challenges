<?php

namespace IWD\JOBINTERVIEW\Entity;


class QCM extends Question
{
  public function getAnswer(): array
  {
    return $this->answer;
  }

  public function setAnswer(array $answer)
  {
    $this->answer = $answer;
  }
}