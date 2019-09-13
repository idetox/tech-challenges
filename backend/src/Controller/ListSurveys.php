<?php

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ListSurveys.
 * @package IWD\JOBINTERVIEW\Controller
 */
class ListSurveys extends SurveyAbstractController
{
  protected $app;

  public function __construct(BackendApplication $app)
  {
    $this->app = $app;
  }

  /**
   * Print list of survey
   */
  public function __invoke()
  {
    try {
      foreach ($this->app->decodeFiles() as $decodeFile) {
        if ($this->skip($decodeFile, $codes ?? [])) {
          continue;
        }
        $codes[] = $decodeFile['survey']['code'];
        $surveys[] = $decodeFile['survey'];
      }
    } catch (\Exception $e) {
      return new JsonResponse([
        'error'=>get_class($e),
        'trace' => $e->getTrace()
      ],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    return new JsonResponse($this->sortSurvey($surveys ?? []), JsonResponse::HTTP_OK);
  }

  /**
   * Sort survey by name
   */
  private function sortSurvey(array $surveys)
  {
    usort($surveys, function ($a, $b) {
      return strnatcmp($a['name'], $b['name']);
    });
    return $surveys;
  }

  /**
   * skip file when survey already registered
   * @param array $data
   * @param array $codes
   * @return bool
   */
  public function skip(array $data, $codes): bool
  {
    $code = $data['survey']['code'] ?? null;
    return null === $code || \in_array($code, $codes);
  }
}