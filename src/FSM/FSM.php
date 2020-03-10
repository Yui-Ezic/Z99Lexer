<?php


namespace Z99Lexer\FSM;


use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Graphp\GraphViz\GraphViz;
use LogicException;

class FSM
{
    public const DEFAULT_STATE = 'default';

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var State
     */
    private $start_state;

    /**
     * @var State[]
     */
    private $states = [];

    public function __construct()
    {
        $this->graph = new Graph();
    }

    public function visualize() : void
    {
        $graphviz = new GraphViz();
        $graphviz->display($this->graph);
    }

    public function addStart($id): State
    {
        $vertex = $this->createVertex($id);
        return $this->start_state = $this->addStateByVertex($id, $vertex);
    }

    public function getStartState() : State
    {
        if ($this->start_state === null) {
            throw new LogicException('Final state not set');
        }

        return $this->start_state;
    }

    public function addState($id) : State
    {
        $vertex = $this->createVertex($id);
        $vertex->setAttribute('graphviz.color', 'green');
        return $this->addStateByVertex($id, $vertex);
    }

    public function addTrigger($trigger, $from, $to) : void
    {
        $from = $this->states[$from];
        $to = $this->states[$to];

        if ($trigger === self::DEFAULT_STATE) {
            $from->setDefault($to);
        } else {
            $from->addTrigger($trigger, $to);
        }
    }

    public function addFinalState($id, callable $callback, $needNext = true) : State
    {
        $vertex = $this->createVertex($id);
        $vertex->setAttribute('graphviz.color', 'blue');
        $vertex->setAttribute('final', true);
        $vertex->setAttribute('callback', $callback);
        $vertex->setAttribute('needNext', $needNext);
        return $this->addStateByVertex($id, $vertex);
    }

    private function createVertex($id): Vertex
    {
        $vertex = $this->graph->createVertex($id);
        $vertex->setAttribute('graphviz.label', $id);
        return $vertex;
    }

    private function addStateByVertex($id, Vertex $vertex) : State
    {
        return $this->states[$id] = new State($vertex);
    }


}