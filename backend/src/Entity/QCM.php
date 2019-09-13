<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Entity;

class QCM extends Question
{
    public function getAnswer(): array
    {
        return $this->answer;
    }

    public function setAnswer(array $answer): void
    {
        $this->answer = $answer;
    }
}
