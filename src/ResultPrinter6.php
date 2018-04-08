<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Runner\Version;

if (version_compare(Version::series(),'6.99.99','<=')) {
    class _ResultPrinter extends \PHPUnit\TextUI\ResultPrinter
    {
        public function startTest(\PHPUnit\Framework\Test $test)
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
}
