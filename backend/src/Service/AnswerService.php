<?php

declare(strict_types=1);

namespace IWD\JOBINTERVIEW\Service;

use IWD\JOBINTERVIEW\Exception\FileMalformedException;

/**
 * Class AnswerService.
 */
class AnswerService
{
    public $qcm;
    public $numeric;
    public $date;

    public function __construct()
    {
        $this->qcm = $this->initField('qcm');
        $this->numeric = $this->initField('numeric');
        $this->date = $this->initField('date');
    }

    /**
     * Instantiate fields.
     */
    private function initField(string $type): array
    {
        switch ($type) {
            case 'numeric':
                return [
                    'label' => '',
                    'answer' => 0,
                    'count' => 0,
                ];
                break;
            case 'qcm':
            case 'date':
                return [
                    'label' => '',
                    'answer' => [],
                ];
                break;
        }
    }

    /**
     *  Loop over questions in files to aggregate answers.
     */
    public function aggregateQuestions(array $questions, $type = null): array
    {
        foreach ($questions as $question) {
            if (null !== $type && $this->skipQuestion($question, $type)) {
                continue;
            }
            $this->aggregateQuestion($question);
        }

        return [
            'qcm' => $this->qcm,
            'numeric' => $this->numeric,
            'date' => $this->date,
        ];
    }

    /**
     * QCM : Increment product values
     * Numeric : Add all numeric values
     * Date : List all dates.
     */
    private function aggregateQuestion(array $question): void
    {
        switch (strtolower($question['type'])) {
            case 'qcm':
                $this->countAnswers($question);
                break;
            case 'numeric':
                $this->countNumerics($question);
                break;
            case 'date':
                $this->saveDates($question);
                break;
        }
    }

    /**
     * Count number of products.
     */
    private function countAnswers(array $question): void
    {
        $options = $question['options'];
        $this->qcm['label'] = $question['label'];
        array_walk($question['answer'], function ($item, $key) use ($options): void {
            isset($this->qcm['answer'][$options[$key]]) ? ($this->qcm['answer'][$options[$key]] += $item ? 1 : 0)
                : $this->qcm['answer'][$options[$key]] = $item ? 1 : 0;
        });
    }

    /**
     * Add numeric values, do average later.
     */
    private function countNumerics(array $question): void
    {
        $this->numeric['label'] = $question['label'] ?? '';
        if (isset($question['answer'])) {
            $this->numeric['answer'] += $question['answer'];
        }
        ++$this->numeric['count'];
    }

    /**
     * List all dates.
     */
    private function saveDates(array $question): void
    {
        $this->date['label'] = $question['label'] ?? '';
        $this->date['answer'][] = $question['answer'] ?? '';
    }

    /**
     * Filter question by type.
     */
    private function skipQuestion(array $data, string $type): bool
    {
        return $type !== $data['type'];
    }

    /**
     * Render questions.
     *
     * @throws FileMalformedException
     */
    public function renderQuestions(array $data, ?string $type = null): array
    {
        $numeric = $data['numeric'] ?? [];
        $date = $data['date'] ?? [];
        if (!isset($numeric['count'], $numeric['answer'], $date['answer'])) {
            throw new FileMalformedException('Files malformed');
        }
        switch ($type) {
            case 'qcm':
                return $this->showQcm($data);
                break;
            case 'numeric':
                return $this->showNumeric($numeric);
                break;
            case 'date':
                return $this->showDate($date);
                break;
            case null:
                return [
                    $this->showQcm($data),
                    $this->showNumeric($numeric),
                    $this->showDate($date),
                ];
            default:
                return [];
        }
    }

    /**
     * Print QCM values.
     */
    private function showQcm(array $data): array
    {
        return ['qcm' => $data['qcm']];
    }

    /**
     * Print Numeric values.
     */
    private function showNumeric(array $numeric): array
    {
        // Get average & remove total count
        $numeric['answer'] = 0 === $numeric['count'] ? 0 : (float) $numeric['answer'] / $numeric['count'];
        unset($numeric['count']);

        return ['numeric' => $numeric];
    }

    /**
     * Print Date values.
     */
    private function showDate(array $date): array
    {
        // Get unique date & sort them by desc
        $date['answer'] = array_unique($date['answer']);
        rsort($date['answer']);

        return ['date' => $date];
    }
}
