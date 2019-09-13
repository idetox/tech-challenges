<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Entity;

class Numeric extends Question
{
    public function getAnswer(): int
    {
        return $this->answer;
    }

    public function setAnswer(int $answer): void
    {
        $this->answer = $answer;
    }
}
