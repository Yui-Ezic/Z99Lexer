<?php


namespace Z99Lexer;


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