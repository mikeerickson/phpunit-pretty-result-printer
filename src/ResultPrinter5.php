<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit_Framework_Test;
use PHPUnit_TextUI_ResultPrinter;
use function get_class;

class ResultPrinter5 extends PHPUnit_TextUI_ResultPrinter
{
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->className = get_class($test);
        parent::startTest($test);
    }
}
