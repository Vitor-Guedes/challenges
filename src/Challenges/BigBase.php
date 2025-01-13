<?php

namespace Guedes\Challenges\Challenges;

use Exception;
use Guedes\Challenges\Challenge;

class BigBase extends Challenge
{
    protected string $stack = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    public function resolve()
    {
        if (! $this->hasFile()) {
            return [];
        }

        $content = $this->getFileContent();

        $rows = explode("\n", $content);
        $results = [];

        foreach ($rows as $base) {
            try {
                $results[$base] = $this->resolveBase($base);
            } catch (Exception $e) {
                $results[$base] = $e->getMessage();
            }
        }

        return $results;
    }

    protected function resolveBase(string $base)
    {
        [$from, $to, $input] = explode(' ', $base);

        if (($from <= 2 || $from > 61) || ($to < 2 || $to > 61)) {
            throw new Exception('???');
        }

        $result = base_convert($input, $from, $to);

        return $result;
    }
}