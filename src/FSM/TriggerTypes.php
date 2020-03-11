<?php


namespace Z99Lexer\FSM;


class TriggerTypes
{
    public const LETTER = 0;
    public const DIGIT = 1;
    public const DOT = 2;
    public const WS = 3;
    public const EOL = 4;
    public const EOF = 5;

    /**
     * @param $char
     * @return mixed
     */
    public static function getType($char) {
        if (ctype_alpha($char)) {
            return self::LETTER;
        }

        if (ctype_digit($char)) {
            return self::DIGIT;
        }

        if ($char === '.') {
            return self::DOT;
        }

        if ($char === ' ' || $char === "\t" || $char === "\u{000D}") {
            return self::WS;
        }

        if ($char === "\n" || $char === "\r\n") {
            return self::EOL;
        }

        if ($char === "\u{0000}") {
            return self::EOF;
        }

        return $char;
    }

    public static function getTypeName($type) {
        $typeNames = [
            self::LETTER => 'chr',
            self::DIGIT => 'dgt',
            self::DOT => 'dot',
            self::WS => 'WS',
            self::EOL => 'EOL',
            self::EOF => 'EOF',
        ];

        return array_key_exists($type, $typeNames) ? $typeNames[$type] : $type;
    }
}