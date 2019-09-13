<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Exception\FileMalformedException;
use IWD\JOBINTERVIEW\Exception\SurveyNotFoundException;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ListAnswers.
 */
class ListAnswers extends AnswerAbstractController
{
    private $request;
    private $app;
    private $answerService;

    public function __construct(BackendApplication $app, Request $request, AnswerService $answerService)
    {
        $this->request = $request;
        $this->app = $app;
        $this->answerService = $answerService;
    }

    public function __invoke()
    {
        $code = $this->request->get('code');
        try {
            foreach ($this->app->decodeFiles() as $decodeFile) {
                if ($this->skip($decodeFile, $code)) {
                    continue;
                }
                $data = $this->answerService->aggregateQuestions($decodeFile['questions']);
            }
            if (empty($data)) {
                throw new SurveyNotFoundException('Survey not found');
            }
            $response = new JsonResponse($this->answerService->renderQuestions($data ?? []), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (FileMalformedException $e) {
            $response = new JsonResponse([
                'code' => 'files_malformed',
                'message' => $e->getMessage(),
                'url' => $this->app->url('list_answers', ['code' => $code]),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        } catch (SurveyNotFoundException $e) {
            $response = new JsonResponse([
                'code' => 'survey_not_found',
                'message' => $e->getMessage(),
                'url' => $this->app->url('list_answers', ['code' => $code]),
            ], JsonResponse::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        return $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $code
     */
    public function skip(array $data, $code): bool
    {
        return $code !== $data['survey']['code'];
    }
}
