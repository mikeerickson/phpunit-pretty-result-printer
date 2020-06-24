<?php

require_once 'PrinterInit.php';

(new PrinterInit())->init('always', $_SERVER['argv']);
