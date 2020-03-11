<?php


namespace Z99Lexer\LexerInterfaces;


interface LexerWriterInterface
{
    /**
     * Adds const to table without repetition.
     *
     * @param $const number
     * @return int id of this const.
     */
    public function addConst($const) : int;

    /**
     * Adds identifier to table without repetition.
     *
     * @param $name
     * @param $type
     * @return int id of the added identifier
     */
    public function addIdentifier($name, $type = null) : int;

    /**
     * @param int $line line number
     * @param string $string substring with this token
     * @param $token
     * @param int $index optional index of constants or Ids table
     */
    public function addToken(int $line, string $string, $token, int $index = null) : void;
}