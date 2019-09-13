<?php

namespace IWD\JOBINTERVIEW\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Study
{
  private $survey;
  private $questions;

  public function __construct()
  {
    $this->questions = new ArrayCollection();
  }

  public function getSurvey(): Survey
  {
    return $this->survey;
  }

  public function setSurvey(Survey $survey): void
  {
    $this->survey = $survey;
  }

  public function getQuestions(): array
  {
    return $this->questions->toArray();
  }

  public function addQuestions(Question $question): void
  {
    if(!$this->questions->contains($question)){
      $this->questions->add($question);
    }
  }

  public function removeQuestions(Question $question): void
  {
    if($this->questions->contains($question)){
      $this->questions->removeElement($question);
    }
  }
}