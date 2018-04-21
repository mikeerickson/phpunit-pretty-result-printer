<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Runner\Version;
use PHPUnit\TextUI\ResultPrinter;

if (version_compare(Version::series(), '6.99.99', '<=')) {
    class ResultPrinter6 extends ResultPrinter
    {
        public function startTest(Test $test)
        {
            $this->className = \get_class($test);
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
}
