<?php


namespace Z99Lexer\Stream;


use OutOfRangeException;

class FileStream implements CharStreamInterface
{
    private $source;
    private $current = 0;
    private $length;

    /**
     * FileStream constructor.
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->source = str_split(file_get_contents($filename));
        $this->length = count($this->source);
    }

    /**
     * @inheritDoc
     */
    public function read(): string
    {
        if ($this->current === $this->length) {
            $this->current++;
            return CharStreamInterface::EOF;
        }

        if ($this->current > $this->length) {
            throw new OutOfRangeException('Input stream has already ended.');
        }

        return $this->source[$this->current++];
    }
}