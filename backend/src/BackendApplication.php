<?php

namespace IWD\JOBINTERVIEW;


use IWD\JOBINTERVIEW\Traits\DecodeFileTrait;
use Silex\Application;

/**
 * Class BackendApplication.
 * @package IWD\JOBINTERVIEW
 */
class BackendApplication extends Application
{
  use DecodeFileTrait;
  use Application\UrlGeneratorTrait;
}
