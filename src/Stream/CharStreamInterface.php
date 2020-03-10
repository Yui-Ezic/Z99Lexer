<?php


namespace Z99Lexer\Stream;


use OutOfRangeException;

interface CharStreamInterface
{
    public const EOF = "\u{0000}";

    /**
     * Returns next character from input stream.
     * Return EOF in the end of stream.
     *
     * @return string
     * @throws OutOfRangeException
     */
    public function read() : string;
}