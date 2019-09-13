<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Entity;

class Date extends Question
{
    public function getAnswer(): \DateTime
    {
        return $this->answer;
    }

    public function setAnswer(\DateTimeInterface $answer): void
    {
        $this->answer = $answer;
    }
}
