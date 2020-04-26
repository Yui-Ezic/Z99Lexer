<?php


namespace Z99Lexer\Exceptions;


use LogicException;
use Throwable;

class LexerException extends LogicException
{
    private $errorLine;
    private $string;


    public function __construct(string $message, string $string, int $line, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->string = $string;
        $this->errorLine = $line;
    }

    public function getString() : string
    {
        return $this->string;
    }

    public function getErrorLine() : int
    {
        return $this->errorLine;
    }
}