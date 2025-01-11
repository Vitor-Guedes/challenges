<?php

namespace Guedes\Challenges\Challenges;

use Exception;
use Guedes\Challenges\Challenge;

/**
 * https://osprogramadores.com/desafios/d14/
 * 
 * Desafio - Expressões Numéricas
 */
class NumericalExpressions extends Challenge
{    
    /**
     * @return array
     */
    public function resolve()
    {
        $options = getopt('', ["expression::"]);

        if (isset($options['expression'])) {
            try {
                $results = [];
                $expression = $options['expression'];
                $results[$expression] = $this->resolveExpression($expression);
            } catch (Exception $e) {
                $results[$expression] = $e->getMessage();
            }
            return $results;
        }

        if (! $this->hasFile()) {
            return ;
        }

        $content = $this->getFileContent();

        $rows = explode("\n", $content);
        $results = [];

        foreach ($rows as $expression) {
            try {
                $results[$expression] = $this->resolveExpression($expression);
            } catch (Exception $e) {
                $results[$expression] = $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * @param string $expression
     * 
     * @return string|int
     */
    protected function resolveExpression(string $expression): string|int
    {
        $stack = $this->getStack($expression);

        $this->validateParentheses($stack);
        $this->validateSyntax($stack);

        $operatorsStack = $this->sortOperators($stack);

        $cnt = 1;
        foreach ($operatorsStack as $index => $operator) {
            if ($operator === '(') {
                $nextOperator = array_slice($operatorsStack, $cnt++, 1, true);

                $closeIndex = key($nextOperator);
                if ($nextOperator[$closeIndex] === ')') {
                    $newStack = array_slice($stack, $index + 1, ($closeIndex - $index) - 1, true);
                    
                    $result = $this->resolveExpression(implode('', $newStack));

                    $stack = $this->replace($stack, $index + 1, ($closeIndex - $index) + 1, $result);

                    return $this->resolveExpression(implode('', $stack));
                } else {
                    continue ;
                }
            }

            $result = $this->calculate($stack, $index);

            $stack = $this->replace($stack, $index, 3, $result);

            if (count($stack) > 1) {
                return $this->resolveExpression(implode('', $stack));
            }
        }

        return implode('', $stack);
    }

    /**
     * @param array $stack
     * 
     * @throws Exception
     * @return void
     */
    protected function validateParentheses(array $stack): void
    {
        $openParentheses = array_search('(', $stack);
        $closeParentheses = array_search(')', $stack);

        if ($openParentheses === false && $closeParentheses) {
            throw new Exception('#1 - Sintaxy error: ' . implode('', $stack));
        }

        if ($openParentheses && $closeParentheses === false) {
            throw new Exception('#2 - Sintaxy error: ' . implode('', $stack));
        }

        $opens = array_filter($stack, function ($operator) {
            return $operator === '(';
        });
        $closes = array_filter($stack, function ($operator) {
            return $operator === ')';
        });

        if (count($opens) !== count($closes)) {
            throw new Exception('#3 - Sintaxy error: ' . implode('', $stack));
        }
    }

    /**
     * @param array $stack
     * 
     * @throws Exception
     * @return void
     */
    protected function validateSyntax(array $stack): void
    {
        $errorSyntaxy = [];
        preg_match('/[\^|\+|\-|\*|\/]{2}/', implode('', $stack), $errorSyntaxy);

        if ($errorSyntaxy) {
            throw new Exception('#3 - Sintaxy error: ' . implode('', $stack));
        }
    }

    /**
     * @param array $stack
     * @param int $index
     * @param int $length
     * @param mixed $replacement
     * 
     * @return array
     */
    protected function replace(array $stack = [], $index = 0, int $length = 3, mixed $replacement = []): array
    {
        array_splice($stack, $index - 1, $length, $replacement);
        return $stack;
    }

    /**
     * @param array $stack
     * @param int $index
     * 
     * @return int|float
     */
    protected function calculate(array $stack, int $index): int|float
    {
        $n1 = $stack[$index - 1];
        $n2 = $stack[$index + 1];
        $operator = $stack[$index];

        if ($operator === '/' && $n2 == 0) {
            throw new Exception("Não pode dividir por 0");
        }

        return match ($stack[$index]) {
            '+' => $n1 + $n2,
            '-' => $n1 - $n2,
            '*' => $n1 * $n2,
            '/' => $n1 / $n2,
            '^' => pow($n1, $n2)
        };
    }

    /**
     * @param string $expression
     * 
     * @return array
     */
    protected function getStack(string $expression): array
    {
        $pattern = "/(\d+(\.\d+)?|\^|\+|\-|\*|\/|\(|\))/";

        preg_match_all($pattern, $expression, $stack);

        return $stack[0];
    }

    /**
     * @param array $stack
     * 
     * @return array
     */
    protected function sortOperators(array $stack)
    {
        $operatorsIndex = [
            '(' => 0, 
            ')' => 0, 
            '^' => 1,
            '*' => 2, 
            '/' => 2, 
            '+' => 3, 
            '-' => 3
        ];

        $filterOperators = array_filter($stack, function ($operator) {
            return in_array($operator, ['(', ')', '*', '/', '+', '*', '-', '^']);
        });

        $sorteredOperator = $filterOperators;
        uasort($sorteredOperator, function($current, $next) use ($operatorsIndex) {
            if ($operatorsIndex[$current] === $operatorsIndex[$next]) {
                return 0;
            }
            return $operatorsIndex[$current] > $operatorsIndex[$next] ? 1 : -1;
        });

        return $sorteredOperator;
    }

    /**
     * @return string
     */
    protected function getFileContent(): string
    {
        if (! $this->fileExists()) {
            throw new Exception('Arquido passado não existe.');
        }
        return file_get_contents($this->getFile());
    }
}