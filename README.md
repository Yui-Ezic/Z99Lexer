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

```php
require 'vendor/autoload.php';

$fsm = new Z99Lexer\FSM\FSM();
```

The initialization of the state graph occurs in the file "create_fsm.php". You can run ```visualize()``` 
method to see graph of states as picture.

> dgt - digit  
> chr - character  
> def - default  
> WS - white space  

![gra3231 tmp](https://user-images.githubusercontent.com/21062493/76415790-8971a680-63a2-11ea-9a0b-93469e8555a0.png)

For convenience, all final states begin with a minus and are highlighted in blue. 0 is start state.

## Create own grammar
Firstly create the start State.

```php
$fsm->addStart(0);
```

Than several intermediate state

```php
$fsm->addState(1);
$fsm->addState(2);
```

And add final state which has callback function which handle the substring and adds 
token to tokens table. The last argument tells the lexer when it's move to the initial 
state whether to take the next character or not.

```php
$keywords = [
    'program', 'var', 'begin', 
    'read', 'write', 'repeat', 
    'until', 'if', 'then', 'fi'
];
$types = ['int', 'real', 'bool'];
$boolConstants = ['true', 'false'];

$fsm->addFinalState(-2, 
    static function (LexerWriterInterface $writer, string $string, int $line) 
    use ($keywords, $types, $boolConstants) {
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

$fsm->addFinalState('error', 
    static function (LexerWriterInterface $writer, string $string, int $line) {
        throw new LexerException('Unknown char.', $string, $line);
    });
```

Then adds triggers (edges of graph)

```php
$fsm->addTrigger(TriggerTypes::LETTER, 0, 1);
$fsm->addTrigger(FSM::DEFAULT_STATE, 0, 'error');

$fsm->addTrigger(TriggerTypes::LETTER, 1, 1);
$fsm->addTrigger(FSM::DEFAULT_STATE, 1, -2);
$fsm->addTrigger(TriggerTypes::DIGIT, 1, 2);

$fsm->addTrigger(FSM::DEFAULT_STATE, 2, -2);
$fsm->addTrigger(TriggerTypes::LETTER, 2, 2);
$fsm->addTrigger(TriggerTypes::DIGIT, 2, 2);
```

And display the graph of states

```php
$fsm->visualize();
```

![gra283B tmp](https://user-images.githubusercontent.com/21062493/76421275-15d49700-63ac-11ea-9c3f-cdabe7a79033.png)
## Lexer
To create tables of tokens, constants and identifiers you need to create Lexer class
which receives CharStreamInterface and FSM with our grammar.

```$xslt
$stream = new FileStream('example.z99'); // implements CharStreamInterface
$lexer = new Lexer($stream, $fsm);
```

And run a ```tokenize()``` method

```php
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
