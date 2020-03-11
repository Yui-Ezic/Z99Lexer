<?php


namespace Z99Lexer;


use Z99Lexer\FSM\FSM;
use Z99Lexer\FSM\State;
use Z99Lexer\LexerInterfaces\LexerInterface;
use Z99Lexer\Stream\CharStreamInterface;

class Lexer implements LexerInterface
{
    /**
     * @var int
     */
    private $constId = 0;

    /**
     * @var Constant[]
     */
    private $constants = [];

    /**
     * @var int
     */
    private $identifierId = 0;

    /**
     * @var Identifier[]
     */
    private $identifiers = [];

    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @var CharStreamInterface
     */
    private $stream;

    /**
     * @var FSM
     */
    private $fsm;

    /**
     * @var State
     */
    private $state;

    /**
     * Lexer constructor.
     * @param CharStreamInterface $stream
     * @param FSM $fsm
     */
    public function __construct(CharStreamInterface $stream, FSM $fsm)
    {
        $this->stream = $stream;

        $this->fsm = $fsm;

        $this->state = $fsm->getStartState();
    }

    /**
     * @inheritDoc
     */
    public function tokenize() : void
    {
        $char = $this->stream->read();
        if ($char === CharStreamInterface::EOF) {
            return;
        }

        $string = '';
        $line = 1;

        $done = false;

        while (!$done) {
            if ($char === CharStreamInterface::EOF) {
                $done = True;
            }

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

                if (!$done && $this->state->isNeedNext()) {
                    $char = $this->stream->read();
                }

                $this->state = $this->fsm->getStartState();
            } elseif (!$done) {
                $char = $this->stream->read();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addConst($const): int
    {
        if ($id = $this->findConst($const)) {
            return $id;
        }

        $id = $this->constId++;
        $this->constants[$id] = new Constant($id, $const);

        return $id;
    }

    /**
     * Returns id of constant if find else null
     *
     * @param $const
     * @return int|null
     */
    private function findConst($const) : ?int
    {
        $array = array_map(static function (Constant $constant) {
            return $constant->getValue();
        }, $this->constants);

        if ($id = array_search($const, $array, true)) {
            return $id;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function addIdentifier($name, $type = null): int
    {
        if ($id = $this->findIdentifier($name)) {
            return $id;
        }

        $id = $this->identifierId++;
        $this->identifiers[$id] = new Identifier($id, $name, $type);

        return $id;
    }

    /**
     * Returns id of identifier if find else null
     *
     * @param $name
     * @return int|null
     */
    private function findIdentifier($name) : ?int
    {
        $array = array_map(static function (Identifier $constant) {
            return $constant->getName();
        }, $this->identifiers);

        if ($id = array_search($name, $array, true)) {
            return $id;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function addToken(int $line, string $string, $token, int $index = null): void
    {
        $this->tokens[] = new Token($line, $string, $token, $index);
    }

    /**
     * @return Constant[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return Identifier[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }
}