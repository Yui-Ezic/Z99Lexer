<?php

require 'vendor/autoload.php';

use Z99Lexer\Constant;
use Z99Lexer\Exceptions\LexerException;
use Z99lexer\FSM\FSM;
use Z99Lexer\Identifier;
use Z99Lexer\Lexer;
use Z99Lexer\Stream\FileStream;
use Z99Lexer\Token;

function token_to_string(Token $token) {
    return sprintf('@%02d  %-10s %-10s %2s',
            $token->getLine(),
            $token->getType(),
            "'" . $token->getString() . "'",
            (string)$token->getIndex() ?: 'NULL'
        );
}

function constant_to_string(Constant $constant) {
    return sprintf('@%02d  %-10s',
        $constant->getId(),
        (string)$constant->getValue()
    );
}

function identifier_to_string(Identifier $identifier) {
    return sprintf('@%02d  %-10s %-10s',
        $identifier->getId(),
        $identifier->getName(),
        $identifier->getType()
    );
}


/**
 * @var $fsm FSM
 */
$fsm = require 'create_fsm.php';

$stream = new FileStream('example.z99');
$lexer = new Lexer($stream, $fsm);

try {
    $lexer->tokenize();

    $file = fopen('output.txt', 'wb');

    fwrite($file, 'Tokens:' . PHP_EOL);
    foreach ($lexer->getTokens() as $token) {
        $string = token_to_string($token) . PHP_EOL;
        fwrite($file, $string);
    }
    fwrite($file, PHP_EOL);

    fwrite($file, 'Constants:' . PHP_EOL);
    foreach ($lexer->getConstants() as $const) {
        $string = constant_to_string($const) . PHP_EOL;
        fwrite($file, $string);
    }
    fwrite($file, PHP_EOL);

    fwrite($file, 'Identifiers:' . PHP_EOL);
    foreach ($lexer->getIdentifiers() as $identifier) {
        $string = identifier_to_string($identifier) . PHP_EOL;
        fwrite($file, $string);
    }
    fwrite($file, PHP_EOL);

} catch (LexerException $e) {
    echo $e->getMessage() .
        "\n With string: '" . $e->getString() . '\'' .
        "\n in line " . $e->getLine();
}


