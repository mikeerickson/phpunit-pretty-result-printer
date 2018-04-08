<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Runner\Version;

$low = version_compare(Version::series(), '7.1', '>=');

// setting this to true for future compatibility release (ie PHPUnit 7.2.x)
// If a future version of PHPUnit breaks, additional result printer classes will be created

$high = true; // version_compare(Version::series(),'7.1.99','<=');

if ($low && $high) {
    class _ResultPrinter extends \PHPUnit\TextUI\ResultPrinter
    {
        public function startTest(\PHPUnit\Framework\Test $test): void
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
