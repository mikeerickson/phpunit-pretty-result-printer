<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit_Framework_Test;
use PHPUnit_TextUI_ResultPrinter;

if (class_exists(PHPUnit_TextUI_ResultPrinter::class)) {
    class ResultPrinter5 extends PHPUnit_TextUI_ResultPrinter
    {
        public function startTest(PHPUnit_Framework_Test $test)
        {
            $this->className = \get_class($test);
            parent::startTest($test);
        }
    }
}
