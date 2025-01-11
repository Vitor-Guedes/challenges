<?php

namespace Guedes\Challenges;

class Manager
{
    protected $challenge;

    public function __construct(protected array $options = [])
    {
        
    }

    public function resolve()
    {
        $this->identifyChallenge();
    }

    protected function identifyChallenge()
    {
        $content = file_get_contents(__DIR__ . '/MapperChallenges.json');

        $mapper = json_decode($content, true);

        $this->challenge = new $mapper['commands'][$this->options['c']];

        $this->challenge;
    }
}