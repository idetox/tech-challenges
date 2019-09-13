<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Traits;

use Symfony\Component\Finder\Finder;

/**
 * Trait DecodeFileTrait.
 */
trait DecodeFileTrait
{
    /**
     * Decode json files into array.
     */
    public function decodeFiles($filter = '*.json', string $dir = 'data'): \Generator
    {
        foreach ($this->loadFiles($filter, $dir) as $file) {
            $decodedFile = $this['serializer']->decode($file->getContents(), 'json');
            yield $decodedFile;
        }
    }

    /**
     * Load files from directory.
     */
    public function loadFiles($filter, string $dir): Finder
    {
        if (!\defined('ROOT_PATH')) {
            \define('ROOT_PATH', realpath('.'));
        }
        $finder = new Finder();

        return $finder->files()->name($filter)->in(ROOT_PATH.'/'.$dir);
    }
}
