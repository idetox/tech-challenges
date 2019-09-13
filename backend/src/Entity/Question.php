<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Entity;

abstract class Question
{
    protected $label;
    protected $options;
    protected $answer;

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }
}
