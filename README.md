# PHP lexer for Z99
Z99 is Pascal-like programming language developed for education purposes.
Example of program written on Z99
```
program first
var i: int;
    sum, value : real;
begin
    sum = 0.0;
    i = 1;
    repeat
        read (value);
        sum = sum + value;
        write(i, sum);
        i = i + 1;
    until i <= 100;

    sum = sum / 100;
    write(sum);
end.
```

## Initialization of grammar
Lexer written with using the [Finite State Machine](https://en.wikipedia.org/wiki/Finite-state_machine)
which is represented by Z99Lexer\FSM\FSM class.

```$xslt
require 'vendor/autoload.php';

$fsm = new Z99Lexer\FSM\FSM();
```

The initialization of the state graph occurs in the file "create_fsm.php". You can run ```visualize()``` 
method to see graph of states as picture.

![gra3231 tmp](https://user-images.githubusercontent.com/21062493/76415790-8971a680-63a2-11ea-9a0b-93469e8555a0.png)

For convenience, all final states begin with a minus and are highlighted in blue. 0 is start state.

All final state has callback function which handle the substring and add token to tokens table.


```
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
```

## Lexer
To create tables of tokens, constants and identifiers you need to create Lexer class
which receives CharStreamInterface and FSM with our grammar.

```$xslt
$stream = new FileStream('example.z99'); // implements CharStreamInterface
$lexer = new Lexer($stream, $fsm);
```

And run a ```tokenize()``` method

```$xslt
try {
    $lexer->tokenize();

    foreach ($lexer->getTokens() as $token) {
        echo $token . PHP_EOL;
    }

    foreach ($lexer->getConstants() as $const) {
        echo $const . PHP_EOL;
    }

    foreach ($lexer->getIdentifiers() as $identifier) {
        echo $identifier . PHP_EOL;
    }

} catch (LexerException $e) {
    echo $e->getMessage() .
        "\n With string: '" . $e->getString() . '\'' .
        "\n in line " . $e->getLine();
}
```
