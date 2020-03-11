<?php

require 'vendor/autoload.php';

use Z99Lexer\Exceptions\LexerException;
use Z99lexer\FSM\FSM;
use Z99Lexer\Lexer;
use Z99Lexer\Stream\FileStream;

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
        $string = $token . PHP_EOL;
        fwrite($file, $string);
    }

    fwrite($file, PHP_EOL . 'Constants:' . PHP_EOL);
    foreach ($lexer->getConstants() as $const) {
        $string = $const . PHP_EOL;
        fwrite($file, $string);
    }

    fwrite($file, PHP_EOL . 'Identifiers:' . PHP_EOL);
    foreach ($lexer->getIdentifiers() as $identifier) {
        fwrite($file, $identifier . PHP_EOL);
    }

} catch (LexerException $e) {
    echo $e->getMessage() .
        "\n With string: '" . $e->getString() . '\'' .
        "\n in line " . $e->getLine();
}


