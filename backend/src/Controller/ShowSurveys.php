<?php

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Exception\SurveyNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShowSurveys extends SurveyAbstractController
{
    protected $app;
    protected $request;

    public function __construct(BackendApplication $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    public function __invoke()
    {
        $code = $this->request->get('code');
        try {
            foreach ($this->app->decodeFiles() as $decodeFile) {
                if ($this->skip($decodeFile, $code)) {
                    continue;
                }
                $survey = $decodeFile['survey'];
                break;
            }

            if (empty($survey)) {
                throw new SurveyNotFoundException('Survey not found');
            }
            $response = new JsonResponse($survey, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (SurveyNotFoundException $e) {
            $response = new JsonResponse([
                'code' => 'survey_not_found',
                'message' => $e->getMessage(),
                'url' => $this->app->url('show_surveys', ['code' => $code])
            ], JsonResponse::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }
        return $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Filter answers by code
     * @param array $data
     * @param string $code
     * @return bool
     */
    public function skip(array $data, $code): bool
    {
        return $code !== $data['survey']['code'];
    }


}
