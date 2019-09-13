<?php

namespace IWD\JOBINTERVIEW\Entity;


class Numeric extends Question
{
  public function getAnswer(): int
  {
    return $this->answer;
  }

  public function setAnswer(int $answer)
  {
    $this->answer = $answer;
  }
}