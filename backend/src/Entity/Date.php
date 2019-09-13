<?php

namespace IWD\JOBINTERVIEW\Entity;


class Date extends Question
{

  public function getAnswer(): \DateTime
  {
    return $this->answer;
  }

  public function setAnswer(\DateTimeInterface $answer)
  {
    $this->answer = $answer;
  }
}