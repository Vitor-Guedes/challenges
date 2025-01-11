<?php

namespace Guedes\Challenges\Challenges;

use Exception;
use Guedes\Challenges\Challenge;

class PowerOfTwo extends Challenge
{
    /**
     * @return array
     */
    public function resolve(): array
    {
        if (! $this->hasFile()) {
            return [];
        }

        $content = $this->getFileContent();

        $rows = explode("\n", $content);
        $results = [];

        foreach ($rows as $number) {
            try {
                $results[$number] = $this->resolveNumeric((int) $number);
            } catch (Exception $e) {
                $results[$number] = $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * @param int $number
     * 
     * @return string
     */
    protected function resolveNumeric(int $number): string|bool
    {
        if ($number <= 0) {
            return false;
        }

        $count = 0;
        $_number = $number;
        while (($_number % 2) == 0) {
            $count++;
            $_number /= 2;
        }

        $result = $_number == 1;
        return ! $result ? "$number false" : "$number true $count"; 
    }
}