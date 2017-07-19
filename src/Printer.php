<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit_Framework_Test;

/**
 * Class Printer
 *
 * @license MIT
 */
class Printer extends \PHPUnit_TextUI_ResultPrinter
{
    /**
     * @var string
     */
    private $className = '';

    /**
     * @var string
     */
    private $lastClassName = '';

    /**
     * @var int
     */
    private $maxClassNameLength = 40;

    /**
     * @var int
     */
    private $maxNumberOfColumns;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $out = null,
        $verbose = false,
        $colors = self::COLOR_DEFAULT,
        $debug = false,
        $numberOfColumns = 80
    ) {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);

        $this->maxNumberOfColumns = $numberOfColumns;
        $this->maxClassNameLength = intval($numberOfColumns * 0.5);
    }

    public function packageName()
    {
        return "PHPUnit Pretty Result Printer";
    }

    /**
     * {@inheritdoc}
     */
    protected function writeProgress($progress)
    {
        if (! $this->debug) {
            $this->printClassName();
        }

        $this->printTestCaseStatus('', $progress);
    }

    /**
     * {@inheritdoc}
     */
    protected function writeProgressWithColor($color, $buffer)
    {
//        if ($this->debug) {
//            parent::writeProgressWithColor($color, $buffer);
//            return;
//        }

//        $this->printClassName();
        $this->printTestCaseStatus($color, $buffer);
    }

    /**
     * @param string $color
     * @param string $buffer Result of the Test Case => . F S I R
     */
    private function printTestCaseStatus($color, $buffer)
    {
        if ($this->column == $this->maxNumberOfColumns) {
            $this->writeNewLine();
            $padding = $this->maxClassNameLength;
            $this->column = $padding;
            echo str_pad(' ', $padding) . "\t";
        }

        switch (strtoupper($buffer)) {
            case '.':
                $color = 'fg-green,bold';
                $buffer = mb_convert_encoding("\x27\x14", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : $this->appendLabel('Passed');
                break;
            case 'S':
                $color = 'fg-yellow,bold';
                $buffer = '►';
//                $buffer = mb_convert_encoding("\x27\x6F", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : $this->appendLabel('Skipped');

                break;
            case 'I':
                $color = 'fg-blue,bold';
//                $buffer = mb_convert_encoding("\x27\x5A", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : $this->appendLabel('Incomplete');
                break;
            case 'F':
                $color = 'fg-red,bold';
                $buffer = mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : $this->appendLabel('Fail');
                break;
            case 'E':
                $color = 'fg-red,bold';
                $buffer = '⚈';
                $buffer .= (!$this->debug) ? '' : $this->appendLabel('Error');
                break;
        }

        $buffer .= ' ';
        echo parent::formatWithColor($color, $buffer);
        if($this->debug) {
            $this->writeNewLine();
        }
        $this->column++;
    }

    private function appendLabel($label) {
        return ' ' . $label;
    }

    /**
     * {@inheritdoc}
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->className = get_class($test);
        parent::startTest($test);
    }

    /**
     * Prints the Class Name if it has changed
     */
    protected function printClassName()
    {
        if ($this->lastClassName === $this->className) {
            return;
        }

        echo PHP_EOL;
        $className = $this->formatClassName($this->className);
        if ($this->colors === true) {
            $this->writeWithColor('fg-cyan', $className, false);
        } else {
            $this->write($className);
        }
        $this->column += strlen($className) + 4;
        echo "\t";

        $this->lastClassName = $this->className;
    }

    /**
     * @param string $className
     * @return string
     */
    private function formatClassName($className)
    {
        if (strlen($className) <= $this->maxClassNameLength) {
            return $this->fillWithWhitespace($className);
        }

        return '...' . substr($className, strlen($className) - $this->maxClassNameLength, $this->maxClassNameLength);
    }

    /**
     * @param string $className
     * @return string;
     */
    private function fillWithWhitespace($className)
    {
        return str_pad($className, $this->maxClassNameLength);
    }
}
