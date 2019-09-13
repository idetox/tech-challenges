<?php

namespace IWD\JOBINTERVIEW\Traits;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Exception\UnsupportedException;

/**
 * Trait DecodeFileTrait
 * @package IWD\JOBINTERVIEW\Traits
 */
trait DecodeFileTrait
{
  /**
   * Decode json files into array
   * Supported format : json, xml
   * @throws UnsupportedException
   */
  public function decodeFiles(string $ext = 'json', string $dir = 'data'): \Generator
  {
    if (!in_array($ext, ['json', 'xml'])) {
      throw new UnsupportedException('Invalid format');
    }
    foreach ($this->loadFiles("*.$ext", $dir) as $file) {
      $decodedFile = $this['serializer']->decode($file->getContents(), $ext);
      yield $decodedFile;
    }
  }

  /**
   * Load files from directory
   */
  public function loadFiles(string $pattern, string $dir): Finder
  {
    $finder = new Finder();
    return $finder->files()->name($pattern)->in(ROOT_PATH . '/' . $dir);
  }
}