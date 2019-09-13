<?php

namespace IWD\JOBINTERVIEW\Normalizer;

use IWD\JOBINTERVIEW\Entity\Date;
use IWD\JOBINTERVIEW\Entity\Survey;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DateNormalizer extends GetSetMethodNormalizer
{
  public function denormalize($data, $type, $format = null, array $context = [])
  {
    $data['answer'] = $this->serializer->denormalize($data['answer'], \DateTimeInterface::class);
    return parent::denormalize($data, $type, $format, $context);
  }

  public function supportsDenormalization($data, $type, $format = null)
  {
    return parent::supportsDenormalization($data, $type, $format) && Date::class === $type;
  }
}