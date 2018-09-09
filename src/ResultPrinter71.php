<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Runner\Version;
use PHPUnit\TextUI\ResultPrinter;
use Bakyt\Console\Phanybar;

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

        protected function printFooter(TestResult $result): void
        {
            parent::printFooter($result);

            if ($this->anyBarEnabled) {
                $phanyBar = new Phanybar();
                if (sizeof($result->failures())) {
                    // if errors, we will always show red bar
                    $phanyBar->send('red', $this->anyBarPort);
                } else {
                    // if no errors and successful, show green
                    if ($result->wasSuccessful()) {
                        $phanyBar->send('green', $this->anyBarPort);
                    } else {
                        // otherwise show yellow for remaining
                        $phanyBar->send('yellow', $this->anyBarPort);
                    }
                }
            }
        }
    }
}
