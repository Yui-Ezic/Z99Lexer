<?php


namespace Z99Lexer\LexerInterfaces;


use Z99Lexer\Exceptions\LexerException;

interface LexerInterface extends LexerWriterInterface, LexerReaderInterface
{
    /**
     * Creates tokens, constants and identifiers tables from source program text.
     *
     * @throws LexerException
     */
    public function tokenize() : void;
}