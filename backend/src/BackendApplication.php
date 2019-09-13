<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW;

use IWD\JOBINTERVIEW\Traits\DecodeFileTrait;
use Silex\Application;

/**
 * Class BackendApplication.
 */
class BackendApplication extends Application
{
    use DecodeFileTrait;
    use Application\UrlGeneratorTrait;
}
