<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Runner\Version;

$low = version_compare(Version::series(), '7.0', '>=');
$high = version_compare(Version::series(), '7.0.99', '<=');

if ($low && $high) {
    class _ResultPrinter extends \PHPUnit\TextUI\ResultPrinter
    {
        public function startTest(\PHPUnit\Framework\Test $test): void
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
