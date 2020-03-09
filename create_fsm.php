<?php

return (static function (){
    $fsm = new \Z99Lexer\FSM\FSM();

    $fsm->addStart(0);

    $fsm->addState(1);
    $fsm->addState(2);
    $fsm->addState(3);
    $fsm->addState(4);
    $fsm->addState(5);
    $fsm->addState(6);
    $fsm->addState(7);
    $fsm->addState(8);

    $fsm->addFinalState(-1, static function (string $string) {
        if ($string === 'if') {
            return 'keyword';
        }
    }, false);
    $fsm->addFinalState(-2, static function () {
    }, false);
    $fsm->addFinalState(-3, static function () {
    }, false);
    $fsm->addFinalState(-4, static function () {
    }, false);
    $fsm->addFinalState(-5, static function () {
    }, false);
    $fsm->addFinalState(-6, static function () {
    }, false);
    $fsm->addFinalState(-7, static function () {
    }, false);
    $fsm->addFinalState(-8, static function () {
    }, false);
    $fsm->addFinalState(-9, static function () {
    }, false);
    $fsm->addFinalState(-10, static function () {
    }, false);
    $fsm->addFinalState(-11, static function () {
    }, false);
    $fsm->addFinalState(-12, static function () {
    }, false);
    $fsm->addFinalState(-13, static function () {
    }, false);
    $fsm->addFinalState(-14, static function () {
        return 'RelOp';
    });

    $fsm->addFinalState('error', static function () {
    }, false);
    $fsm->addFinalState('error2', static function () {
    }, false);

    $fsm->addTrigger('ws', 0, 0);
    $fsm->addTrigger('letter', 0, 1);
    $fsm->addTrigger('digit', 0, 4);
    $fsm->addTrigger('dot', 0, 6);
    $fsm->addTrigger('=', 0, 7);
    $fsm->addTrigger('>', 0, 8);
    $fsm->addTrigger('<', 0, 8);
    $fsm->addTrigger('!', 0, 8);
    $fsm->addTrigger('+', 0, -6);
    $fsm->addTrigger('-', 0, -7);
    $fsm->addTrigger('*', 0, -8);
    $fsm->addTrigger('/', 0, -9);
    $fsm->addTrigger('(', 0, -11);
    $fsm->addTrigger(')', 0, -12);
    $fsm->addTrigger('dot', 0, -13);
    $fsm->addTrigger('>', 0, -14);
    $fsm->addTrigger('other', 0, 'error');

    $fsm->addTrigger('letter', 1, 1);
    $fsm->addTrigger('dot', 1, 2);
    $fsm->addTrigger('other', 1, -1);
    $fsm->addTrigger('digit', 1, 3);

    $fsm->addTrigger('other', 2, -1);

    $fsm->addTrigger('other', 3, -2);
    $fsm->addTrigger('letter', 3, 3);
    $fsm->addTrigger('digit', 3, 3);

    $fsm->addTrigger('digit', 4, 4);
    $fsm->addTrigger('other', 4, -3);
    $fsm->addTrigger('dot', 4, 5);

    $fsm->addTrigger('other', 5, -4);
    $fsm->addTrigger('digit', 5, 5);

    $fsm->addTrigger('digit', 6, 5);
    $fsm->addTrigger('other', 6, -13);

    $fsm->addTrigger('other', 7, -5);
    $fsm->addTrigger('=', 7, -10);

    $fsm->addTrigger('=', 8, -10);
    $fsm->addTrigger('other', 8, 'error2');

    return $fsm;
})();