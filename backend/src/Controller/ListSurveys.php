<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ListSurveys.
 */
class ListSurveys extends SurveyAbstractController
{
    protected $app;

    public function __construct(BackendApplication $app)
    {
        $this->app = $app;
    }

    /**
     * Print list of survey.
     */
    public function __invoke(): JsonResponse
    {
        $codes = [];
        foreach ($this->app->decodeFiles() as $decodeFile) {
            if ($this->skip($decodeFile, $codes)) {
                continue;
            }
            $survey = $decodeFile['survey'];
            $codes[] = $survey['code'];
            $surveys[] = $survey;
        }

        $response = new JsonResponse($this->sortSurvey($surveys ?? []), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);

        return $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Sort survey by name.
     */
    private function sortSurvey(array $surveys): array
    {
        if (empty($surveys)) {
            return [];
        }
        // Sort survey by code asc
        usort($surveys, function ($a, $b) {
            return strnatcmp($a['code'], $b['code']);
        });

        return $surveys;
    }

    /**
     * Skip file when survey already registered.
     *
     * @param array $codes
     */
    public function skip(array $data, $codes): bool
    {
        $code = $data['survey']['code'] ?? null;

        return null === $code || \in_array($code, $codes, true);
    }
}
