<?php

namespace IWD\JOBINTERVIEW\Controller;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Exception\FileMalformedException;
use IWD\JOBINTERVIEW\Exception\WrongTypeException;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShowAnswers.
 * @package IWD\JOBINTERVIEW\Controller
 */
class ShowAnswers extends AnswerAbstractController
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
        $type = $this->request->get('type');
        try {
            if (!\in_array($type, [null, 'qcm', 'numeric', 'date'], true)) {
                throw new WrongTypeException('Wrong answer type');
            }
            foreach ($this->app->decodeFiles() as $decodeFile) {
                if ($this->skip($decodeFile, $code)) {
                    continue;
                }
                $data = $this->answerService->aggregateQuestions($decodeFile['questions'], $type);
            }
            $response = new JsonResponse($this->answerService->renderQuestions($data ?? [], $type), JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        } catch (WrongTypeException $e) {
            $response = new JsonResponse([
                'code' => 'unknown_type',
                'message' => $e->getMessage(),
                'url' => $this->app->url('show_answers', ['code' => $code, 'type' => $type]),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY, ['Content-Type' => 'application/json']);
        } catch (FileMalformedException $e) {
            $response = new JsonResponse([
                'code' => 'files_malformed',
                'message' => $e->getMessage(),
                'url' => $this->app->url('show_answers', ['code' => $code, 'type' => $type]),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
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
