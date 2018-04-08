<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

if (class_exists('\PHPUnit_TextUI_ResultPrinter')) {
    class _ResultPrinter extends \PHPUnit_TextUI_ResultPrinter
    {
        public function startTest(\PHPUnit_Framework_Test $test)
        {
            $this->className = \get_class($test);
            parent::startTest($test);
        }
    }
}
