<?php

namespace IWD\JOBINTERVIEW\Normalizer;

use IWD\JOBINTERVIEW\Entity\Date;
use IWD\JOBINTERVIEW\Entity\Numeric;
use IWD\JOBINTERVIEW\Entity\QCM;
use IWD\JOBINTERVIEW\Entity\Study;
use IWD\JOBINTERVIEW\Entity\Survey;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class StudyNormalizer extends GetSetMethodNormalizer
{
  public function denormalize($data, $type, $format = null, array $context = [])
  {
    $data['survey'] = $this->serializer->denormalize($data['survey'],Survey::class);
    foreach($data['questions'] as $question) {
      $questions[] = $this->denormalizeQuestion($question);
    }
    $data['questions'] = $questions;
    return parent::denormalize($data, $type, $format, $context);
  }

  public function supportsDenormalization($data, $type, $format = null)
  {
    return parent::supportsDenormalization($data, $type, $format) && Study::class === $type && isset($data['survey']) && isset($data['questions']);
  }

  private function denormalizeQuestion(array $question)
  {
    switch(strtolower($question['type']))
    {
      case 'qcm':
        return $this->serializer->denormalize($question,QCM::class);
        break;
      case 'numeric':
        return $this->serializer->denormalize($question,Numeric::class);
        break;
      case 'date':
        return $this->serializer->denormalize($question,Date::class);
        break;
    }
  }
}