<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\TextUI\ResultPrinter;

class ResultPrinter70 extends ResultPrinter
{
    public function startTest(Test $test): void
    {
        $this->className = \get_class($test);
        parent::startTest($test);
    }

    protected function writeProgress($progress): void
    {
        $this->writeProgressEx($progress);
    }

    protected function writeProgressWithColor($progress, $buffer): void
    {
        $this->writeProgressWithColorEx($progress, $buffer);
    }
}
