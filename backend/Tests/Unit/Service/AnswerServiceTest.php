<?php

namespace IWD\JOBINTERVIEW\Tests\Unit\Service;

use IWD\JOBINTERVIEW\BackendApplication;
use IWD\JOBINTERVIEW\Service\AnswerService;
use Silex\WebTestCase;

class AnswerServiceTest extends WebTestCase
{
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(AnswerService::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testInitFields()
    {
        $answerService = new AnswerService();
        $this->assertSame(['label' => '', 'answer' => 0, 'count' => 0], $answerService->numeric);
        $this->assertSame(['label' => '', 'answer' => []], $answerService->date);
        $this->assertSame(['label' => '', 'answer' => []], $answerService->qcm);
    }

    public function testAggregateQuestions()
    {
        $answerService = new AnswerService();
        $data = [
            [
                'type' => 'qcm',
                'label' => 'What best sellers are available in your store?',
                'options' => [
                    'Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6'
                ],
                'answer' => [
                    true, true, true, false, true, false
                ]
            ],
            [
                'type' => 'numeric',
                'label' => 'Number of products?',
                'options' => null,
                'answer' => 5200
            ],
            [
                'type' => 'date',
                'label' => 'What is the visit date?',
                'options' => null,
                'answer' => '2017-08-25T12:04:50.000Z'
            ]
        ];
        $expected = $this->mockAggregatedData();

        $this->assertSame($expected, $answerService->aggregateQuestions($data));
    }

    public function testAggregateQuestionQCM()
    {
        $answerService = new AnswerService();
        $aggregateQuestion = self::getMethod('aggregateQuestion');
        $data = [
            'type' => 'qcm',
            'label' => 'What best sellers are available in your store?',
            'options' => [
                'Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6'
            ],
            'answer' => [
                true, true, true, false, true, false
            ]
        ];
        $expected = [
            'label' => 'What best sellers are available in your store?',
            'answer' => [
                'Product 1' => 1,
                'Product 2' => 1,
                'Product 3' => 1,
                'Product 4' => 0,
                'Product 5' => 1,
                'Product 6' => 0
            ],
        ];
        $aggregateQuestion->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->qcm);
    }

    public function testAggregateQuestionNumeric()
    {
        $answerService = new AnswerService();
        $aggregateQuestion = self::getMethod('aggregateQuestion');
        $data = [
            'type' => 'numeric',
            'label' => 'Number of products?',
            'options' => null,
            'answer' => 5200
        ];
        $expected = [
            'label' => 'Number of products?',
            'answer' => 5200,
            'count' => 1,
        ];
        $aggregateQuestion->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->numeric);
    }

    public function testAggregateQuestionDate()
    {
        $answerService = new AnswerService();
        $aggregateQuestion = self::getMethod('aggregateQuestion');
        $data = [
            'type' => 'date',
            'label' => 'What is the visit date?',
            'options' => null,
            'answer' => '2017-08-25T12:04:50.000Z'
        ];
        $expected = [
            'label' => 'What is the visit date?',
            'answer' => ['2017-08-25T12:04:50.000Z']
        ];
        $aggregateQuestion->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->date);
    }

    public function testCountAnswers()
    {
        $answerService = new AnswerService();
        $countAnswers = self::getMethod('countAnswers');
        $data = [
            'type' => 'qcm',
            'label' => 'What best sellers are available in your store?',
            'options' => [
                'Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6'
            ],
            'answer' => [
                true, true, true, false, true, false
            ]
        ];
        $expected = [
            'label' => 'What best sellers are available in your store?',
            'answer' => [
                'Product 1' => 1,
                'Product 2' => 1,
                'Product 3' => 1,
                'Product 4' => 0,
                'Product 5' => 1,
                'Product 6' => 0
            ],
        ];
        $countAnswers->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->qcm);
    }

    public function testCountNumerics()
    {
        $answerService = new AnswerService();
        $countNumerics = self::getMethod('countNumerics');
        $data = [
            'type' => 'numeric',
            'label' => 'Number of products?',
            'options' => null,
            'answer' => 5200
        ];
        $expected = [
            'label' => 'Number of products?',
            'answer' => 5200,
            'count' => 1,
        ];
        $countNumerics->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->numeric);
    }

    public function testSaveDates()
    {
        $answerService = new AnswerService();
        $saveDates = self::getMethod('saveDates');
        $data = [
            'type' => 'date',
            'label' => 'What is the visit date?',
            'options' => null,
            'answer' => '2017-08-25T12:04:50.000Z'
        ];
        $expected = [
            'label' => 'What is the visit date?',
            'answer' => ['2017-08-25T12:04:50.000Z']
        ];
        $saveDates->invokeArgs($answerService, [$data]);
        $this->assertSame($expected, $answerService->date);
    }

    public function testSkipQuestionFalse()
    {
        $answerService = new AnswerService();
        $skipQuestion = self::getMethod('skipQuestion');

        $this->assertFalse($skipQuestion->invokeArgs($answerService, [['type' => 'qcm'], 'qcm']));
    }

    public function testSkipQuestionTrue()
    {
        $answerService = new AnswerService();
        $skipQuestion = self::getMethod('skipQuestion');

        $this->assertTrue($skipQuestion->invokeArgs($answerService, [['type' => 'numeric'], 'qcm']));
    }

    public function testRenderQuestionsEmpty()
    {
        $answerService = new AnswerService();
        $data = $this->mockAggregatedData();

        $this->assertSame([], $answerService->renderQuestions($data, 'test'));
    }

    public function testRenderQuestionsQCM()
    {
        $answerService = new AnswerService();
        $data = $this->mockAggregatedData();

        $expected = [
            'qcm' => [
                'label' => 'What best sellers are available in your store?',
                'answer' => [
                    'Product 1' => 1,
                    'Product 2' => 1,
                    'Product 3' => 1,
                    'Product 4' => 0,
                    'Product 5' => 1,
                    'Product 6' => 0
                ],
            ]
        ];
        $this->assertSame($expected, $answerService->renderQuestions($data, 'qcm'));
    }

    public function testRenderQuestionsNumeric()
    {
        $answerService = new AnswerService();
        $data = $this->mockAggregatedData();

        $expected = [
            'numeric' => [
                'label' => 'Number of products?',
                'answer' => (float)5200
            ]
        ];
        $this->assertSame($expected, $answerService->renderQuestions($data, 'numeric'));
    }

    public function testRenderQuestionsDate()
    {
        $answerService = new AnswerService();
        $data = $this->mockAggregatedData();

        $expected = [
            'date' => [
                'label' => 'What is the visit date?',
                'answer' => ['2017-08-25T12:04:50.000Z']
            ]
        ];
        $this->assertSame($expected, $answerService->renderQuestions($data, 'date'));
    }

    public function testShowQcm()
    {
        $answerService = new AnswerService();
        $showQcm = self::getMethod('showQcm');
        $data = $this->mockAggregatedData();
        $expected = [
            'qcm' => [
                'label' => 'What best sellers are available in your store?',
                'answer' => [
                    'Product 1' => 1,
                    'Product 2' => 1,
                    'Product 3' => 1,
                    'Product 4' => 0,
                    'Product 5' => 1,
                    'Product 6' => 0
                ],
            ]
        ];

        $this->assertSame($expected, $showQcm->invokeArgs($answerService, [$data]));
    }

    public function testShowNumeric()
    {
        $answerService = new AnswerService();
        $showNumeric = self::getMethod('showNumeric');
        $data = $this->mockAggregatedData();
        $expected = [
            'numeric' => [
                'label' => 'Number of products?',
                'answer' => (float)5200,
            ]
        ];

        $this->assertSame($expected, $showNumeric->invokeArgs($answerService, [$data['numeric']]));
    }

    public function testShowDate()
    {
        $answerService = new AnswerService();
        $showDate = self::getMethod('showDate');
        $data = $this->mockAggregatedData();
        $expected = [
            'date' => [
                'label' => 'What is the visit date?',
                'answer' => ['2017-08-25T12:04:50.000Z']
            ]
        ];

        $this->assertSame($expected, $showDate->invokeArgs($answerService, [$data['date']]));
    }

    public function createApplication(): BackendApplication
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', realpath('.'));
        }
        return require __DIR__ . '/../../../src/Client/Webapp/app.php';
    }

    private function mockAggregatedData()
    {
        return [
            'qcm' => [
                'label' => 'What best sellers are available in your store?',
                'answer' => [
                    'Product 1' => 1,
                    'Product 2' => 1,
                    'Product 3' => 1,
                    'Product 4' => 0,
                    'Product 5' => 1,
                    'Product 6' => 0
                ],
            ],
            'numeric' => [
                'label' => 'Number of products?',
                'answer' => 5200,
                'count' => 1,
            ],
            'date' => [
                'label' => 'What is the visit date?',
                'answer' => ['2017-08-25T12:04:50.000Z']
            ]
        ];
    }
}
