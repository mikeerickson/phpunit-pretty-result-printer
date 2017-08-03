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
    private $maxClassNameLength = 35;

    /**
     * @var int
     */
    private $maxNumberOfColumns;

    /**
     * @var
     */
    private $hideClassName;

    /**
     * @var
     */
    private $simpleOutput;


    /**
     * {@inheritdoc}
     */
    public function __construct($out = null, $verbose = false, $colors = self::COLOR_DEFAULT, $debug = false, $numberOfColumns = 80)
    {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);

        $this->maxNumberOfColumns = $numberOfColumns;
        $this->maxClassNameLength = intval($numberOfColumns * 0.5);

        $this->hideClassName      = getenv('CD_PRINTER_HIDE_CLASS')    || $this->config('cd-printer-hide-class');
        $this->simpleOutput       = getenv('CD_PRINTER_SIMPLE_OUTPUT') || $this->config('cd-printer-simple-output');
    }

    /**
     * @return string
     */
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
                $buffer = mb_convert_encoding("\x27\x13", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Passed';
                break;
            case 'S':
                $color = 'fg-yellow,bold';
                $buffer = ($this->simpleOutput) ? 'S' : mb_convert_encoding("\x27\xA6", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Skipped';

                break;
            case 'I':
                $color = 'fg-blue,bold';
                $buffer = ($this->simpleOutput) ? 'I' : 'ℹ';
                $buffer .= (!$this->debug) ? '' : ' Incomplete';
                break;
            case 'F':
                $color = 'fg-red,bold';
                $buffer = mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Fail';
                break;
            case 'E':
                $color = 'fg-red,bold';
                $buffer = ($this->simpleOutput) ? 'E' : '⚈';
                $buffer .= (!$this->debug) ? '' : ' Error';
                break;
        }

        $buffer .= ' ';
        echo parent::formatWithColor($color, $buffer);
        if ($this->debug) {
            $this->writeNewLine();
        }
        $this->column++;
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
        if ($this->hideClassName) {
            return;
        }
        if ($this->lastClassName === $this->className) {
            return;
        }

        echo PHP_EOL;
        $className = ' ==> ' .$this->formatClassName($this->className);

        ($this->colors) ? $this->writeWithColor('fg-cyan,bold', $className, false) : $this->write($className);

        $this->column += strlen($className) + 4;
//        echo "\t";

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

        // maxLength of class, minus leading (...) and trailing space
        $maxLength = $this->maxClassNameLength - 4;

        // substring class name, providing space for ellipsis and one space at end
        // this result should be combined to equal $this->maxClassNameLength
        return '...' . substr($className, (strlen($className) - $maxLength), $maxLength). ' ';

    }

    /**
     * @param string $className
     * @return string;
     */
    private function fillWithWhitespace($className)
    {
        return str_pad($className, $this->maxClassNameLength);
    }

    /**
     * @param $key
     * @return bool
     */
    private function config($key)
    {
        $result = null;

        // config/printer.php
        $result = (! is_null($result)) ?: $this->configGetValue($key, $result);

        // .printerrc
        $result = (is_null($result)) ?: $this->rcGetValue($key, $result);

        // composer.json
        $result = (is_null($result)) ?: $this->composerGetValue($key, $result);

        // phpunit.xml
        $result = (is_null($result)) ?: $this->phpunitGetValue($key, $result);

        return $result;
    }

    private function configGetValue($key = "", $currValue = null)
    {
        if(file_exists('config/printer.php')) {
            $configs = include('config/printer.php');
            if(array_key_exists($key, $configs)) {
                return $configs[$key];
            }
        } else {
            return $currValue;
        }

        return null;
    }

    private function rcGetValue($key = "", $currValue = null)
    {
        if (file_exists(".printerrc")) {
            $data   = file_get_contents(".printerrc");
            $config = json_decode($data, true);
            if (array_key_exists("config", $config)) {
                if (array_key_exists($key, $config["config"])) {
                    return $config["config"][$key];
                }
            }
            return $currValue;
        } else {
            return $currValue;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    private function composerGetValue($key = "", $currValue = null)
    {
        $data   = file_get_contents("./composer.json");
        $config = json_decode($data, true);

        if (array_key_exists("config", $config)) {
            if (array_key_exists($key, $config["config"])) {
                $currValue = $config["config"][$key];
            }
        }

        return $currValue;
    }

    private function phpunitGetValue($key = "", $currValue = null)
    {
        $key = str_replace("-", "_", strtoupper($key));
        return getenv($key) ? getenv($key) : $currValue;
    }
}
