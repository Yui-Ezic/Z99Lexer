<?php


namespace Z99Lexer;


use Z99Lexer\FSM\FSM;
use Z99Lexer\FSM\State;

class Lexer implements LexerWriterInterface
{
    private $constants = [];
    private $identifiers = [];
    private $tokens = [];


    private $source;

    private $fsm;

    /**
     * @var State
     */
    private $state;

    public function __construct($string, FSM $fsm)
    {
        $this->source = str_split($string);

        $this->fsm = $fsm;

        $this->state = $fsm->getStartState();
    }

    public function tokenize() : void
    {
        $current = 0;
        $length = count($this->source);
        $line = 1;
        $string = '';
        while ($current < $length) {
            $char = $this->source[$current];

            if (in_array($char,["\n", "\r\n", "\n\r'"], true)) {
                $line++;
            }

            $this->state = $this->state->getNextState($char);

            if ($this->state !== $this->fsm->getStartState()) {
                $string .= $char;
            }

            if ($this->state->isFinal()) {
                $this->state->handle($this, $string, $line);
                $string = '';
                if ($this->state->isNeedNext()) {
                    $current++;
                }
                $this->state = $this->fsm->getStartState();
            } else {
                $current++;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addConst($const): int
    {
        if ($id = array_search($const, $this->constants, true)) {
            return $id;
        }

        $id = count($this->constants);
        $this->constants[$id] = $const;

        return $id;
    }

    /**
     * @inheritDoc
     */
    public function addIdentifier($name, $type = null): int
    {
        if ($id = array_search($name, array_map(function ($item) {
            return $item[0];
        },$this->identifiers), true)) {
            return $id;
        }

        $id = count($this->identifiers);
        $this->identifiers[$id] = [$name, $type];

        return $id;
    }

    /**
     * @inheritDoc
     */
    public function addToken(int $line, string $string, $token, int $index = null): void
    {
        $this->tokens[] = [$line, $string, $token, $index];
    }

    /**
     * @return array
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return array
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }
}