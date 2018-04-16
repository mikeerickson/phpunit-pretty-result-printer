<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\TextUI\ResultPrinter;

class ResultPrinter71 extends ResultPrinter
{
    public function startTest(Test $test): void
    {
        $this->className = \get_class($test);
        parent::startTest($test);
    }

    protected function writeProgress(string $progress): void
    {
        $this->writeProgressEx($progress);
    }

    protected function writeProgressWithColor(string $progress, string $buffer): void
    {
        $this->writeProgressWithColorEx($progress, $buffer);
    }
}
