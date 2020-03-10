<?php


namespace Z99Lexer\FSM;


use Fhaculty\Graph\Vertex;
use http\Exception\InvalidArgumentException;
use LogicException;
use Z99Lexer\LexerWriterInterface;

class State
{
    /**
     * @var Vertex
     */
    private $vertex;

    /**
     * @var State[]
     */
    private $triggers = [];

    /**
     * @var State
     */
    private $default;

    public function __construct(Vertex $vertex)
    {
        $this->vertex = $vertex;
    }

    public function getVertex(): Vertex
    {
        return $this->vertex;
    }

    public function setDefault(State $state): void
    {
        $edge = $this->vertex->createEdgeTo($state->getVertex());
        $edge->setAttribute('graphviz.label', ' def ');
        $this->default = $state;
    }

    public function addTrigger($trigger, State $to): State
    {
        $triggerName = TrigerTypes::getTypeName($trigger);
        $edge = $this->vertex->createEdgeTo($to->getVertex());
        $edge->setAttribute('graphviz.label', " $triggerName ");
        return $this->triggers[$trigger] = $to;
    }

    public function getNextState($trigger): State
    {
        $trigger = TrigerTypes::getType($trigger);

        if (!array_key_exists($trigger, $this->triggers)) {
            if ($this->default === null) {
                throw new InvalidArgumentException("Can't find state by trigger: $trigger");
            }

            return $this->default;
        }

        return $this->triggers[$trigger];
    }

    public function isFinal() : bool
    {
        return $this->vertex->getAttribute('final', false);
    }

    public function isNeedNext() : bool
    {
        return $this->vertex->getAttribute('needNext', true);
    }

    public function handle(LexerWriterInterface $writer, string $string, int $line) : void {
        $callback = $this->vertex->getAttribute('callback');

        if ($callback === null) {
            throw new LogicException('Undefined callback');
        }

        /* @var $callback callable */
        $callback($writer, $string, $line);
    }
}
