<?php

require 'vendor/autoload.php';

use Z99Lexer\Exceptions\LexerException;
use Z99lexer\FSM\FSM;
use Z99Lexer\Lexer;
use Z99Lexer\Stream\FileStream;

function token_to_string($token) {
    $line = $token[0] ?? 'NULL';
    $string = $token[1] ?? 'NULL';
    $tokenName = $token[2] ?? 'NULL';
    $index = $token[3] ?? 'NULL';
    return sprintf('@%02d  %-10s %-10s %2s',
            $line,
            $tokenName,
            "'$string'",
            $index
        );
}

function identifier_to_string($id, $identifier) {
    $name = $identifier[0] ?? 'NULL';
    $type = $identifier[1] ?? 'NULL';
    return sprintf('@%02d  %-10s %-10s',
        $id,
        $name,
        $type
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
    foreach ($lexer->getConstants() as $id => $const) {
        $string = "$id $const" . PHP_EOL;
        fwrite($file, $string);
    }
    fwrite($file, PHP_EOL);

    fwrite($file, 'Identifiers:' . PHP_EOL);
    foreach ($lexer->getIdentifiers() as $id => $identifier) {
        $string = identifier_to_string($id, $identifier) . PHP_EOL;
        fwrite($file, $string);
    }
    fwrite($file, PHP_EOL);

} catch (LexerException $e) {
    echo $e->getMessage() .
        "\n With string: '" . $e->getString() . '\'' .
        "\n in line " . $e->getLine();
}


