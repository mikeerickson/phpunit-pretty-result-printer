<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Runner\Version;
use PHPUnit\TextUI\ResultPrinter;

$low  = version_compare(Version::series(), '7.0', '>=');
$high = version_compare(Version::series(), '7.0.99', '<=');

if ($low && $high) {
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
}
