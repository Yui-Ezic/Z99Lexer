<?php


namespace Z99Lexer\LexerInterfaces;


use Z99Lexer\Constant;
use Z99Lexer\Identifier;
use Z99Lexer\Token;

interface LexerReaderInterface
{
    /**
     * Returns Constants table
     *
     * @return Constant[]
     */
    public function getConstants(): array;

    /**
     * Returns Tokens table
     *
     * @return Token[]
     */
    public function getTokens(): array;

    /**
     * Returns Identifiers table
     *
     * @return Identifier[]
     */
    public function getIdentifiers(): array;
}