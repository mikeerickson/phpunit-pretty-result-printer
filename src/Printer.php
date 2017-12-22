<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use Noodlehaus\Config;

// use this entry point for PHPUnit 5.x
if (class_exists('\PHPUnit_TextUI_ResultPrinter')) {
    class _ResultPrinter extends \PHPUnit_TextUI_ResultPrinter
    {
        public function startTest(\PHPUnit_Framework_Test $test)
        {
            $this->className = get_class($test);
            parent::startTest($test);
        }
    }
}

// use this entrypoint for PHPUnit 6.x
if (class_exists('\PHPUnit\TextUI\ResultPrinter')) {
    class _ResultPrinter extends \PHPUnit\TextUI\ResultPrinter
    {
        public function startTest(\PHPUnit\Framework\Test $test)
        {
            $this->className = get_class($test);
            parent::startTest($test);
        }
    }
}


/**
 * Class Printer
 *
 * @license MIT
 */
class Printer extends _ResultPrinter
{
    /**
     * @var string
     */
    public $className = '';

    /**
     * @var string
     */
    private $lastClassName = '';

    /**
     * @var int
     */
    private $maxClassNameLength = 50;

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
        $this->colors             = new Colors;
        $this->configuration      = new Config($this->configFileName);

        $this->maxNumberOfColumns = $this->getWidth();
        $this->maxClassNameLength = min((int) ($this->maxNumberOfColumns / 2), $this->maxClassNameLength);

        // setup module options
        $this->printerOptions     = $this->configuration->all();
        $this->hideClassName      = $this->configuration->get('options.cd-printer-hide-class');
        $this->simpleOutput       = $this->configuration->get('options.cd-printer-simple-output');
        $this->showConfig         = $this->configuration->get('options.cd-printer-show-config');

        if ($this->showConfig) {
            echo PHP_EOL;
            echo $this->colors->yellow() . "PHPUnit Printer Configuration: ". PHP_EOL;
            echo $this->colors->cyan() .$this->configFileName;
            echo $this->colors->reset();
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
        if (! $this->debug) {
            $this->printClassName();
        }

        $this->printTestCaseStatus($color, $buffer);
    }

    /**
     * @param string $color
     * @param string $buffer Result of the Test Case => . F S I R
     */
    private function printTestCaseStatus($color, $buffer)
    {
        if ($this->column >= $this->maxNumberOfColumns) {
            $this->writeNewLine();
            $padding = $this->maxClassNameLength;
            $this->column = $padding;
            echo str_pad(' ', $padding);
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
        $this->column = $this->column + 2;
    }

    /**
     * {@inheritdoc}
     */
    // public function startTest(\PHPUnit_Framework_Test $test)
    // {
    //     $this->className = get_class($test);
    //     parent::startTest($test);
    // }

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
        $className = $this->formatClassName($this->className);
        ($this->colors) ? $this->writeWithColor('fg-cyan,bold', $className, false) : $this->write($className);
        $this->column = strlen($className) + 1;
        $this->lastClassName = $this->className;
    }

    /**
     * @param string $className
     * @return string
     */
    private function formatClassName($className)
    {
        $prefix = ' ==> ';
        $ellipsis = '...';
        $suffix = ' ';
        $formattedClassName = $prefix . $className . $suffix;

        if (strlen($formattedClassName) <= $this->maxClassNameLength) {
            return $this->fillWithWhitespace($formattedClassName);
        }

        // maxLength of class, minus leading (...) and trailing space
        $maxLength = $this->maxClassNameLength - strlen($prefix . $ellipsis . $suffix);

        // substring class name, providing space for ellipsis and one space at end
        // this result should be combined to equal $this->maxClassNameLength
        return $prefix . $ellipsis . substr($className, (strlen($className) - $maxLength), $maxLength) . $suffix;
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

    /**
     * Gets the terminal width.
     *
     * @return int
     */
    private function getWidth()
    {
        return (int) exec('tput cols');
    }
}
