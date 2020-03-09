<?php

require 'vendor/autoload.php';

use Z99lexer\FSM\FSM;

/**
 * @var $fsm FSM
 */
$fsm = require 'create_fsm.php';

$fsm->visualize();

