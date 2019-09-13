<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Service\contracts;

/**
 * Interface SurveyInterface.
 */
interface SurveyInterface
{
    public function skip(array $data, $code): bool;
}
