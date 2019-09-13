<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Normalizer;

use IWD\JOBINTERVIEW\Entity\Date;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

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
