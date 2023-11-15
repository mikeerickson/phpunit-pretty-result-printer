<?php
require_once 'PrinterInit.php';

(new \Codedungeon\PHPUnitPrettyResultPrinter\PrinterInit())->init($_SERVER['argv']);
