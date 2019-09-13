<?php

namespace IWD\JOBINTERVIEW\Service\contracts;

/**
 * Interface SurveyInterface.
 * @package IWD\JOBINTERVIEW\Service\contracts
 */
interface SurveyInterface
{
  /**
   * @param array $data
   * @param mixed $code
   * @return bool
   */
  public function skip(array $data, $code): bool;
}
