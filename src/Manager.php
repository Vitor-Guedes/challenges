<?php

namespace Guedes\Challenges;

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
        $this->identifyChallenge();

        $this->defineOptions();

        return $this->challenge->resolve();
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