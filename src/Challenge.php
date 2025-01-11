<?php

namespace Guedes\Challenges;

abstract class Challenge
{
    protected string $file;

    abstract public function resolve();

    /**
     * @param string $file
     * 
     * @return \Guedes\Challenges\Challenge
     */
    public function setFile(string $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFile(): bool
    {
        return $this->file !== null;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return __DIR__ . '/files/' . $this->file;
    }

    /**
     * @return bool
     */
    public function fileExists(): bool
    {
        return file_exists(__DIR__ . '/files/' . $this->file);
    }

    /**
     * @return string
     */
    protected function getFileContent(): string
    {
        if (! $this->fileExists()) {
            throw new \Exception('Arquido passado não existe.');
        }
        return file_get_contents($this->getFile());
    }
}