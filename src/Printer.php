<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use Noodlehaus\Config;
use PHPUnit_Framework_Test;
use Bakyt\Console\Phanybar;

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
     * @var mixed|null
     */
    private $quiteOutput;

    /**
     * @var anybar
     */
    private $anybar;

    /**
     * @var Config
     */
    private $configuration;

    /**
     * @var string
     */
    private $configFileName = "";

    private $printerOptions;

    /**
     * {@inheritdoc}
     */
    public function __construct($out = null, $verbose = false, $colors = self::COLOR_DEFAULT, $debug = false, $numberOfColumns = 80)
    {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);

        $this->configFileName     = $this->getConfigurationFile("phpunit-printer.yml");
        $this->anybar             = new Phanybar;
        $this->configuration      = new Config($this->configFileName);

        $this->maxNumberOfColumns = $numberOfColumns;
        $this->maxClassNameLength = intval($numberOfColumns * 0.5);

        // setup module options
        $this->printerOptions     = $this->configuration->all();
        $this->hideClassName      = $this->configuration->get('options.cd-printer-hide-class');
        $this->simpleOutput       = $this->configuration->get('options.cd-printer-simple-output');
        $this->quiteOutput        = $this->configuration->get('options.cd-printer-quiet-output');

        if (! $this->quiteOutput) {
            echo PHP_EOL;
            echo "PHPUnit Printer Configuration: ". PHP_EOL;
            echo $this->configFileName;
            echo PHP_EOL .PHP_EOL;
        }

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
                $this->anybar->send('green');
                break;
            case 'S':
                $color = 'fg-yellow,bold';
                $buffer = ($this->simpleOutput) ? 'S' : mb_convert_encoding("\x27\xA6", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Skipped';
                $this->anybar->send('yellow');

                break;
            case 'I':
                $color = 'fg-blue,bold';
                $buffer = ($this->simpleOutput) ? 'I' : 'ℹ';
                $buffer .= (!$this->debug) ? '' : ' Incomplete';
                $this->anybar->send('blue');

                break;
            case 'F':
                $color = 'fg-red,bold';
                $buffer = mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Fail';
                $this->anybar->send('red');

                break;
            case 'E':
                $color = 'fg-red,bold';
                $buffer = ($this->simpleOutput) ? 'E' : '⚈';
                $buffer .= (!$this->debug) ? '' : ' Error';
                $this->anybar->send('rend');

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
     * @param string $configFileName
     * @return string
     */
    public function getConfigurationFile($configFileName = "phpunit-printer.yml")
    {
        $defaultConfigFilename = $this->getPackageRoot() ."/" .$configFileName;

        $configPath = getcwd();
        $filename   = "";

        while (! file_exists($filename)):
            $filename = $configPath ."/" .$configFileName;
            if ($configPath === "/") {
                $filename = $defaultConfigFilename;
            }
            $configPath = dirname($configPath);
        endwhile;

        return $filename;
    }

    /**
     * @return string | returns package root
     */
    private function getPackageRoot()
    {
        return (dirname(dirname(__FILE__)));
    }
}
