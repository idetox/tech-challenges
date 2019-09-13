<?php

namespace IWD\JOBINTERVIEW;


use IWD\JOBINTERVIEW\Traits\DecodeFileTrait;
use Silex\Application;

class BackendApplication extends Application
{
  use DecodeFileTrait;
}