<?php

namespace Guedes\Challenges;

use Exception;

class Manager
{
    /** 
     * @var \Guedes\Challenges\Challenge 
     */
    protected $challenge;

    /**
     * Skip commands definition to challenge
     * 
     * @var array
     */
    protected array $except = [
        'c'
    ];

    /**
     * Mapping of functions according to the parameters passed
     * 
     * @var array
     */
    protected array $sets = [
        'f' => 'setFile'
    ];

    public function __construct(protected array $options = []) { }

    /**
     * Identify challenge by options end resolve
     * 
     * @return mixed
     */
    public function resolve(): mixed
    {
        try {
            $this->identifyChallenge();

            $this->defineOptions();

            return $this->challenge->resolve();
        } catch (Exception $e) {
            return "Error: " . $e->getMessage() . PHP_EOL; 
        }
    }

    /**
     * @param mixed $content
     * 
     * @return void
     */
    public function show($content): void
    {
        if (is_array($content)) {
            echo json_encode($content, JSON_PRETTY_PRINT) . PHP_EOL;
            return ;
        }

        if (is_object($content)) {
            echo serialize($content) . PHP_EOL;
            return ;
        }

        echo $content . PHP_EOL;
    }

    /**
     * Identify Challenge by optoin 'c' value in src/MapperChallenges.json
     * 
     * @return void
     */
    protected function identifyChallenge(): void
    {
        $content = file_get_contents(__DIR__ . '/MapperChallenges.json');

        $mapper = json_decode($content, true);

        if (! isset($this->options['c'])) {
            throw new Exception('Nenhum comando passado');
        }

        if (! isset($mapper['commands'][$this->options['c']])) {
            throw new Exception('Comando não mapeado');
        }

        $this->challenge = new $mapper['commands'][$this->options['c']];
    }

    /**
     * Calls the functions and passes the values ​​according to the parameters passed
     * 
     * @return void
     */
    protected function defineOptions(): void
    {
        foreach ($this->options as $option => $value) {
            if (in_array($option, $this->except)) {
                continue ;
            }

            $set = $this->sets[$option];
            $this->challenge->{$set}($value);
        }
    }
}