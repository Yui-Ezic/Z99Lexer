<?php

use Z99Lexer\Exceptions\LexerException;
use Z99Lexer\FSM\FSM;
use Z99Lexer\FSM\TriggerTypes;
use Z99Lexer\LexerInterfaces\LexerWriterInterface;

return (static function (){

    $keywords = ['program', 'var', 'begin', 'read', 'write', 'repeat', 'until', 'if', 'then', 'fi'];
    $types = ['int', 'real', 'bool'];
    $boolConstants = ['true', 'false'];

    $fsm = new FSM();

    $fsm->addStart(0);

    $fsm->addState(1);
    $fsm->addState(3);
    $fsm->addState(4);
    $fsm->addState(5);
    $fsm->addState(6);
    $fsm->addState(7);
    $fsm->addState(8);

    $fsm->addFinalState(-1, static function (LexerWriterInterface $writer, string $string, int $line) {
        if ($string !== 'end.') {
            throw new LexerException('Unknown keyword.', $string, $line);
        }

        $writer->addToken($line, $string, 'Keyword');
    });

    $fsm->addFinalState(-2, static function (LexerWriterInterface $writer, string $string, int $line) use ($keywords, $types, $boolConstants) {
        $index = null;
        $string = substr($string, 0, -1);
        if (in_array($string, $keywords, true)) {
            $token = 'Keyword';
        } elseif (in_array($string, $types, true)) {
            $token = 'Type';
        } elseif (in_array($string, $boolConstants, true)) {
            $token = 'BoolConst';
        } else {
            $token = 'Ident';
            $index = $writer->addIdentifier($string);
        }

        $writer->addToken($line, $string, $token, $index);
    }, false);

    $fsm->addFinalState(-3, static function (LexerWriterInterface $writer, string $string, int $line) {
        $string = substr($string, 0, -1);
        $index = $writer->addConst((int)$string);
        $writer->addToken($line, $string, 'IntNum', $index);
    }, false);

    $fsm->addFinalState(-4, static function (LexerWriterInterface $writer, string $string, int $line) {
        $string = substr($string, 0, -1);
        $index = $writer->addConst((float)$string);
        $writer->addToken($line, $string, 'RealNum', $index);
    }, false);

    $fsm->addFinalState(-5, static function (LexerWriterInterface $writer, string $string, int $line) {
        $string = substr($string, 0, -1);
        $writer->addToken($line, $string, 'AssignOp');
    }, false);

    $fsm->addFinalState(-6, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Plus');
    });

    $fsm->addFinalState(-7, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Minus');
    });

    $fsm->addFinalState(-8, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Star');
    });

    $fsm->addFinalState(-9, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Slash');
    });

    $fsm->addFinalState(-10, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'RelOp');
    });

    $fsm->addFinalState(-11, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'LBracket');
    });

    $fsm->addFinalState(-12, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'RBracket');
    });

    $fsm->addFinalState(-13, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Dot');
    }, false);

    $fsm->addFinalState(-14, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Comma');
    });

    $fsm->addFinalState(-15, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Colon');
    });

    $fsm->addFinalState(-16, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'Semi');
    });

    $fsm->addFinalState(-17, static function (LexerWriterInterface $writer, string $string, int $line) {
        $writer->addToken($line, $string, 'RelOp');
    }, false);

    $fsm->addFinalState('error', static function (LexerWriterInterface $writer, string $string, int $line) {
        throw new LexerException('Unknown char.', $string, $line);
    }, false);

    $fsm->addTrigger(TriggerTypes::WS, 0, 0);
    $fsm->addTrigger(TriggerTypes::EOL, 0, 0);
    $fsm->addTrigger(TriggerTypes::EOF, 0, 0);
    $fsm->addTrigger(TriggerTypes::LETTER, 0, 1);
    $fsm->addTrigger(TriggerTypes::DIGIT, 0, 4);
    $fsm->addTrigger(TriggerTypes::DOT, 0, 6);
    $fsm->addTrigger('=', 0, 7);
    $fsm->addTrigger('>', 0, 8);
    $fsm->addTrigger('<', 0, 8);
    $fsm->addTrigger('!', 0, 8);
    $fsm->addTrigger('+', 0, -6);
    $fsm->addTrigger('-', 0, -7);
    $fsm->addTrigger('*', 0, -8);
    $fsm->addTrigger('/', 0, -9);
    $fsm->addTrigger('(', 0, -11);
    $fsm->addTrigger(')', 0, -12);
    $fsm->addTrigger(',', 0, -14);
    $fsm->addTrigger(':', 0, -15);
    $fsm->addTrigger(';', 0, -16);
    $fsm->addTrigger(FSM::DEFAULT_STATE, 0, 'error');

    $fsm->addTrigger(TriggerTypes::LETTER, 1, 1);
    $fsm->addTrigger(TriggerTypes::DOT, 1, -1);
    $fsm->addTrigger(FSM::DEFAULT_STATE, 1, -2);
    $fsm->addTrigger(TriggerTypes::DIGIT, 1, 3);

    $fsm->addTrigger(FSM::DEFAULT_STATE, 3, -2);
    $fsm->addTrigger(TriggerTypes::LETTER, 3, 3);
    $fsm->addTrigger(TriggerTypes::DIGIT, 3, 3);

    $fsm->addTrigger(TriggerTypes::DIGIT, 4, 4);
    $fsm->addTrigger(FSM::DEFAULT_STATE, 4, -3);
    $fsm->addTrigger(TriggerTypes::DOT, 4, 5);

    $fsm->addTrigger(FSM::DEFAULT_STATE, 5, -4);
    $fsm->addTrigger(TriggerTypes::DIGIT, 5, 5);

    $fsm->addTrigger(TriggerTypes::DIGIT, 6, 5);
    $fsm->addTrigger(FSM::DEFAULT_STATE, 6, -13);

    $fsm->addTrigger(FSM::DEFAULT_STATE, 7, -5);
    $fsm->addTrigger('=', 7, -10);

    $fsm->addTrigger('=', 8, -10);
    $fsm->addTrigger(FSM::DEFAULT_STATE, 8, -17);

    return $fsm;
})();