<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Util\Filter;
use PHPUnit\Framework\Test;
use PHPUnit\Runner\Version;
use Bakyt\Console\Phanybar;
use PHPUnit\Framework\TestResult;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Framework\TestFailure;

$low  = version_compare(Version::series(), '7.1', '>=');
$high = true; // version_compare(Version::series(),'7.1.99','<=');

if ($low && $high) {
    class ResultPrinter80 extends ResultPrinter
    {
        private $defectListPrinted = false;

        private $reverse = true;

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
                    $phanyBar->send('exclamation', $this->anyBarPort);
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

        protected function formatExceptionMsg($exceptionMessage): string
        {
            $exceptionMessage = preg_replace('/%/u', '%%', $exceptionMessage);

            $exceptionMessage = str_replace("+++ Actual\n", '', $exceptionMessage);
            $exceptionMessage = str_replace("--- Expected\n", '', $exceptionMessage);
            $exceptionMessage = str_replace('@@ @@', '', $exceptionMessage);

            if (strpos($exceptionMessage, 'This test did not perform any assertions') !== false) {
                $exceptionMessage = $this->setMessageColor('risky', 'This test did not perform any assertions.');
            } else {
                $marker = $this->markers['fail'];
                if ($this->colors) {
                    $exceptionMessage = preg_replace('/^(Exception.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
                    $exceptionMessage = preg_replace('/(Failed.*)$/m', "\033[01;31m %1\$s$1\033[0m", $exceptionMessage);
                    $exceptionMessage = preg_replace("/(\-+.*)$/m", "\033[01;32m$1\033[0m", $exceptionMessage);
                    $exceptionMessage = preg_replace("/(\++.*)$/m", "\033[01;31m$1\033[0m", $exceptionMessage);
                }

                $exceptionMessage = sprintf($exceptionMessage, $marker);
            }

            $exceptionMessage = '  ' . $exceptionMessage;

            return "$exceptionMessage";
        }

        protected function printDefectTrace(TestFailure $defect):void
        {
            $this->write($this->formatExceptionMsg($defect->getExceptionAsString()));
            $trace = Filter::getFilteredStacktrace(
                $defect->thrownException()
            );
            if (!empty($trace)) {
                $this->write("\n" . $trace);
            }
            $exception = $defect->thrownException()->getPrevious();
            while ($exception) {
                $this->write(
                    "\nCaused by\n" .
                    TestFailure::exceptionToString($exception) . "\n" .
                    Filter::getFilteredStacktrace($exception)
                );
                $exception = $exception->getPrevious();
            }
        }

        protected function printDefects(array $defects, string $type): void
        {
            $count = \count($defects);

            if ($count == 0) {
                return;
            }

            if ($this->defectListPrinted) {
                $this->write("\n--\n\n");
            }

            $msg = \sprintf("There %s %d %s%s:\n", ($count == 1) ? 'was' : 'were', $count, $type, ($count == 1) ? '' : 's');
            $msg = $this->setMessageColor($type, $msg);

            $this->write($msg);

            $i = 1;

            if ($this->reverse) {
                $defects = \array_reverse($defects);
            }

            foreach ($defects as $defect) {
                $this->printDefect($defect, $i++);
            }

            $this->defectListPrinted = true;
        }

        /**
         * @param string $type
         * @param $msg
         *
         * @return string
         */
        protected function setMessageColor(string $type, $msg): string
        {
            // 30m == gray
            // 31m == red
            // 32m == green
            // 33m == yellow
            // 34m == blue (cyan)
            // 35m == magenta
            // 36m == cyan
            // 37m == white

            $color  = '37';
            $marker = '';

            if (strpos($type, 'failure') !== false || strpos($type, 'error') !== false) {
                $color  = '31';
                $marker = 'fail';
            } elseif (strpos($type, 'incomplete') !== false) {
                $color  = '34';
                $marker = 'incomplete';
            } elseif (strpos($type, 'risky') !== false) {
                $color  = '35';
                $marker = 'risky';
            } elseif (strpos($type, 'skipped') !== false) {
                $color  = '33';
                $marker = 'skipped';
            }

            // if user turned off colors, return msg as we are down
            if (!$this->colors) {
                return $msg;
            }

            // otherwise, we have colors enabled and time to make it pretty
            $testMarker = $this->markers[$marker] ?? '';

            return "\033[01;{$color}m{$testMarker}{$msg}\033[0m";
        }
    }
}
