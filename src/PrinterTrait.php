<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use Noodlehaus\Config;
use Codedungeon\PHPCliColors\Color;

trait PrinterTrait
{
    protected static $init = false;
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
     * @var Config
     */
    private $configuration;
    /**
     * @var string
     */
    private $configFileName;
    private $printerOptions;
    private $showConfig;
    private $passMark;
    private $failMark;

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

        $this->configFileName = $this->getConfigurationFile('phpunit-printer.yml');
        $this->colorsTool = new Color();
        $this->configuration = new Config($this->configFileName);

        $this->maxNumberOfColumns = $this->getWidth();
        $this->maxClassNameLength = min((int) ($this->maxNumberOfColumns / 2), $this->maxClassNameLength);

        // setup module options
        $this->printerOptions = $this->configuration->all();
        $this->hideClassName = $this->configuration->get('options.cd-printer-hide-class');
        $this->simpleOutput = $this->configuration->get('options.cd-printer-simple-output');
        $this->showConfig = $this->configuration->get('options.cd-printer-show-config');

        $this->passMark = $this->configuration->get('marks.cd-pass');
        $this->failMark = $this->configuration->get('marks.cd-fail');

        $this->init();
    }

    /**
     * @param string $configFileName
     *
     * @return string
     */
    public function getConfigurationFile($configFileName = 'phpunit-printer.yml')
    {
        $defaultConfigFilename = $this->getPackageRoot() . DIRECTORY_SEPARATOR . $configFileName;

        $configPath = getcwd();
        $filename = '';

        $continue = true;
        while (!file_exists($filename) && $continue) {
            $filename = $configPath . DIRECTORY_SEPARATOR . $configFileName;
            if (($this->isWindows() && strlen($configPath) === 3) || $configPath === '/') {
                $filename = $defaultConfigFilename;
                $continue = false;
            }
            $configPath = \dirname($configPath);
        }

        return $filename;
    }

    /**
     * @return string | returns package root
     */
    private function getPackageRoot()
    {
        return \dirname(__FILE__, 2);
    }

    /**
     * @return bool
     */
    private function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * Gets the terminal width.
     *
     * @return int
     */
    private function getWidth()
    {
        $width = 0;
        if ($this->isWindows()) {
            return 96; // create a default width to be used on windows
        }

        exec('stty size 2>/dev/null', $out, $exit);

        // 'stty size' output example: 36 120
        if (\count($out) > 0) {
            $width = (int) explode(' ', array_pop($out))[1];
        }

        // handle CircleCI case (probably the same with TravisCI as well)
        if ($width === 0) {
            $width = 96;
        }

        return $width;
    }

    protected function init()
    {
        if (!self::$init) {
            $version = $this->version();
            $name = $this->packageName();
            echo PHP_EOL;
            echo $this->colorsTool->green() . "${name} ${version} by Codedungeon and contributors." . PHP_EOL;
            echo $this->colorsTool->reset();

            if ($this->showConfig) {
                echo $this->colorsTool->white() . 'Configuration: ';
                echo $this->colorsTool->white() . $this->configFileName;
                echo $this->colorsTool->reset();
                echo PHP_EOL . PHP_EOL;
            }

            self::$init = true;
        }
    }

    public function version()
    {
        $content = file_get_contents($this->getPackageRoot() . DIRECTORY_SEPARATOR . 'composer.json');
        if ($content) {
            $content = json_decode($content, true);

            return $content['version'];
        }

        return 'n/a';
    }

    /**
     * @return string
     */
    public function packageName()
    {
        $content = file_get_contents($this->getPackageRoot() . DIRECTORY_SEPARATOR . 'composer.json');
        if ($content) {
            $content = json_decode($content, true);

            return $content['description'];
        }

        return 'n/a';
    }

    protected function writeProgressEx($progress)
    {
        if (!$this->debug) {
            $this->printClassName();
        }
        $this->printTestCaseStatus('', $progress);
    }

    /**
     * Prints the Class Name if it has changed.
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
        $this->colorsTool ? $this->writeWithColor('fg-cyan,bold', $className, false) : $this->write($className);
        $this->column = \strlen($className) + 1;
        $this->lastClassName = $this->className;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    private function formatClassName($className)
    {
        $prefix = ' ==> ';
        $ellipsis = '...';
        $suffix = '   ';
        $formattedClassName = $prefix . $className . $suffix;

        if (\strlen($formattedClassName) <= $this->maxClassNameLength) {
            return $this->fillWithWhitespace($formattedClassName);
        }

        // maxLength of class, minus leading (...) and trailing space
        $maxLength = $this->maxClassNameLength - \strlen($prefix . $ellipsis . $suffix);

        // substring class name, providing space for ellipsis and one space at end
        // this result should be combined to equal $this->maxClassNameLength
        return $prefix . $ellipsis . substr($className, \strlen($className) - $maxLength, $maxLength) . $suffix;
    }

    /**
     * @param string $className
     *
     * @return string;
     */
    private function fillWithWhitespace($className)
    {
        return str_pad($className, $this->maxClassNameLength);
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
                $buffer = $this->simpleOutput ? '.' : mb_convert_encoding("\x27\x13", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Passed';
                break;
            case 'S':
                $color = 'fg-yellow,bold';
                $buffer = $this->simpleOutput ? 'S' : mb_convert_encoding("\x27\xA6", 'UTF-8', 'UTF-16BE');
                $buffer .= !$this->debug ? '' : ' Skipped';
                break;
            case 'I':
                $color = 'fg-blue,bold';
                $buffer = $this->simpleOutput ? 'I' : 'ℹ';
                $buffer .= !$this->debug ? '' : ' Incomplete';
                break;
            case 'F':
                $color = 'fg-red,bold';
                $buffer = $this->simpleOutput ? 'F' : mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Fail';
                break;
            case 'E':
                $color = 'fg-red,bold';
                $buffer = $this->simpleOutput ? 'E' : '⚈';
                $buffer .= !$this->debug ? '' : ' Error';
                break;
        }

        $buffer .= ' ';
        echo parent::formatWithColor($color, $buffer);
        if ($this->debug) {
            $this->writeNewLine();
        }
        $this->column += 2;
    }

    /**
     * {@inheritdoc}
     */
    protected function writeProgressWithColorEx($color, $buffer)
    {
        if (!$this->debug) {
            $this->printClassName();
        }

        $this->printTestCaseStatus($color, $buffer);
    }
}
