<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Runner\Version;
use PHPUnit\TextUI\ResultPrinter;

$low  = version_compare(Version::series(), '7.1', '>=');
$high = true; // version_compare(Version::series(),'7.1.99','<=');

if ($low && $high) {
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
}
