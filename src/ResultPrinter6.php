<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\TextUI\ResultPrinter;
use function get_class;

class ResultPrinter6 extends ResultPrinter
{
    public function startTest(Test $test)
    {
        $this->className = get_class($test);
        parent::startTest($test);
    }

    protected function writeProgress($progress)
    {
        $this->writeProgressEx($progress);
    }

    protected function writeProgressWithColor($progress, $buffer)
    {
        $this->writeProgressWithColorEx($progress, $buffer);
    }
}
