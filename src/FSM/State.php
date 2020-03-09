<?php


namespace Z99Lexer\FSM;


use Fhaculty\Graph\Vertex;
use http\Exception\InvalidArgumentException;

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
        $edge->setAttribute('graphviz.label', ' default ');
        $this->default = $state;
    }

    public function addTrigger($trigger, State $to): State
    {
        $edge = $this->vertex->createEdgeTo($to->getVertex());
        $edge->setAttribute('graphviz.label', " $trigger ");
        return $this->triggers[$trigger] = $to;
    }

    public function getNextState($trigger): State
    {
        if (!array_key_exists($trigger, $this->triggers)) {
            if ($this->default === null) {
                throw new InvalidArgumentException("Can't find state by trigger: $trigger");
            }

            return $this->default;
        }

        return $this->triggers[$trigger];
    }
}
