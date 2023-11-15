<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use Noodlehaus\Config;
use Codedungeon\PHPCliColors\Color;
use Noodlehaus\Exception\EmptyDirectoryException;

trait PrinterTrait8
{
    protected static bool $init = false;
    public string $className = '';
    private string $lastClassName = '';
    private int $maxClassNameLength = 50;
    private int $maxNumberOfColumns = 0;
    private bool $hideClassName = false;
    private bool $simpleOutput = false;
    private Config $configuration;
    private string $configFileName = 'phpunit-printer.yml';
    private array|null $printerOptions;
    private mixed $showConfig = true;
    private bool $hideNamespace = false;
    private bool $dontFormatClassName = false;
    public array $markers = [];
    private array $defaultMarkers = [];
    public Color $colorsTool;
    private array $defaultConfigOptions = [];
    private bool $anyBarEnabled = false;
    private int $anyBarPort = 0;

    /**
     * @throws EmptyDirectoryException
     */
    public function __construct(
        $out = null,
        $verbose = false,
        $colors = self::COLOR_DEFAULT,
        $debug = false,
        $numberOfColumns = 80
    ) {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);

        $this->colorsTool = new Color();

        $this->loadDefaultConfiguration();
        $this->loadUserConfiguration();

        $this->maxNumberOfColumns = $this->getWidth() - 5;
        $this->maxClassNameLength = min((int) ($this->maxNumberOfColumns / 2), $this->maxClassNameLength);

        $this->init($colors);
    }

    public function getConfigurationFile($configFileName = 'phpunit-printer.yml'): string
    {
        $defaultConfigFilename = $this->getPackageRoot() . DIRECTORY_SEPARATOR . 'src/' . $configFileName;

        $configPath = getcwd();
        $filename   = '';

        $continue = true;
        while (!file_exists($filename) && $continue) {
            $filename = $configPath . DIRECTORY_SEPARATOR . $configFileName;
            if (($this->isWindows() && \strlen($configPath) === 3) || $configPath === '/') {
                $filename = $defaultConfigFilename;
                $continue = false;
            }
            $configPath = \dirname($configPath);
        }

        return $filename;
    }

    private function packageName(): string
    {
        $content = file_get_contents($this->getPackageRoot() . DIRECTORY_SEPARATOR . 'composer.json');
        if ($content) {
            $content = json_decode($content, true);
            return $content['description'] ?? '<unknown>';
        }
        return '<unknown>';
    }

    protected function init($use_color = 'always'): void
    {
        if (self::$init) return;

        $name = $this->packageName();

        echo PHP_EOL;
        if ($use_color !== 'never') {
            echo $this->colorsTool->green() . "${name} by Codedungeon and contributors." . PHP_EOL;
            echo $this->colorsTool->reset();
        } else {
            echo "${name} by Codedungeon and contributors." . PHP_EOL;
        }

        if ($this->showConfig) {
            $home     = getenv('HOME');
            $filename = str_replace($home, '~', $this->configFileName);

            if ($use_color !== 'never') {
                echo $this->colorsTool->yellow() . '==> Configuration: ';
                echo $this->colorsTool->yellow() . $filename;
                echo $this->colorsTool->reset();
            } else {
                echo "'==> Configuration: '";
                echo $filename;
            }
            echo PHP_EOL . PHP_EOL;
        }

        self::$init = true;
    }

    protected function writeProgressEx($progress): void
    {
        if (!$this->debug) {
            $this->printClassName();
        }
        $this->printTestCaseStatus('', $progress);
    }

    protected function printClassName(): void
    {
        if ($this->hideClassName) return;
        if ($this->lastClassName === $this->className) return;

        echo PHP_EOL;
        $className = $this->formatClassName($this->className);
        $this->colorsTool ? $this->writeWithColor('fg-cyan,bold', $className, false) : $this->write($className);
        $this->column        = \strlen($className) + 1;
        $this->lastClassName = $this->className;
    }

    protected function writeProgressWithColorEx($color, $buffer): void
    {
        if (!$this->debug) {
            $this->printClassName();
        }

        $this->printTestCaseStatus($color, $buffer);
    }

    /**
     * @throws EmptyDirectoryException
     */
    private function loadDefaultConfiguration(): void
    {
        try {
            $defaultConfig              = new Config($this->getPackageConfigurationFile());
            $this->defaultConfigOptions = $defaultConfig->all();
        } catch (EmptyDirectoryException $e) {
            echo $this->colorsTool->red() . 'Unable to locate phpunit-printer.yml configuration file' . PHP_EOL;
            echo $this->colorsTool->reset();
        }
    }

    private function getPackageConfigurationFile(): string
    {
        return $this->getPackageRoot() . DIRECTORY_SEPARATOR . 'src/phpunit-printer.yml';
    }

    private function loadUserConfiguration(): void
    {
        $this->configFileName = $this->getConfigurationFile();

        try {
            $this->configuration = new Config($this->configFileName);
        } catch (EmptyDirectoryException $e) {
            echo $this->colorsTool->red() . 'Unable to locate valid configuration file' . PHP_EOL;
            echo $this->colorsTool->reset();
        }
        // setup module options
        $this->printerOptions = $this->configuration->all();

        $this->printerOptions = array_merge($this->defaultConfigOptions, $this->printerOptions);

        $this->hideClassName       = $this->getConfigOption('cd-printer-hide-class');
        $this->simpleOutput        = $this->getConfigOption('cd-printer-simple-output');
        $this->showConfig          = $this->getConfigOption('cd-printer-show-config');
        $this->hideNamespace       = $this->getConfigOption('cd-printer-hide-namespace');
        $this->anyBarEnabled       = $this->getConfigOption('cd-printer-anybar');
        $this->anyBarPort          = $this->getConfigOption('cd-printer-anybar-port');
        $this->dontFormatClassName = $this->getConfigOption('cd-printer-dont-format-classname');
        $this->maxClassNameLength  = $this->getConfigOption('cd-printer-max-class-name-length');

        if (!strpos(php_uname(), 'Darwin')) {
            $this->anyBarEnabled = false;
        }

        $this->markers = [
            'pass'         => $this->getConfigMarker('cd-pass'),
            'fail'         => $this->getConfigMarker('cd-fail'),
            'error'        => $this->getConfigMarker('cd-error'),
            'skipped'      => $this->getConfigMarker('cd-skipped'),
            'incomplete'   => $this->getConfigMarker('cd-incomplete'),
            'risky'        => $this->getConfigMarker('cd-risky'),
        ];
    }

    private function getConfigOption($marker, $default = ''): mixed
    {
        if (isset($this->printerOptions['options'])) {
            if (isset($this->printerOptions['options'][$marker])) {
                return $this->printerOptions['options'][$marker];
            }
        }

        return $this->defaultConfigOptions['options'][$marker];
    }

    private function getConfigMarker($marker): mixed
    {
        if (isset($this->printerOptions['markers'])) {
            if (isset($this->printerOptions['markers'][$marker])) {
                return $this->printerOptions['markers'][$marker];
            }
        }

        return $this->defaultConfigOptions['markers'][$marker];
    }

    private function getPackageRoot(): string
    {
        return \dirname(__FILE__, 2);
    }

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function getWidth(): int
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

    private function formatClassName(string $className): string
    {
        $prefix   = ' ==> ';
        $ellipsis = '...';
        $suffix   = '   ';
        if ($this->hideNamespace && strrpos($className, '\\')) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }

        if ($this->dontFormatClassName) {
            return $prefix . $className . $suffix;
        }

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

    private function fillWithWhitespace(string $className): string
    {
        return str_pad($className, $this->maxClassNameLength);
    }

    /**
     * @param string $color
     * @param string $buffer Result of the Test Case => . F S I R
     */
    private function printTestCaseStatus(string $color, string $buffer): void
    {
        if ($this->column >= $this->maxNumberOfColumns) {
            $this->writeNewLine();
            $padding      = $this->maxClassNameLength;
            $this->column = $padding;
            echo str_pad(' ', $padding);
        }
        switch (strtoupper($buffer)) {
            case '.':
                $color  = 'fg-green';
                $buffer = $this->simpleOutput ? '.' : $this->markers['pass']; // mb_convert_encoding("\x27\x13", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Passed';
                break;
            case 'S':
                $color  = 'fg-yellow,bold';
                $buffer = $this->simpleOutput ? 'S' : $this->markers['skipped']; // mb_convert_encoding("\x27\xA6", 'UTF-8', 'UTF-16BE');
                $buffer .= !$this->debug ? '' : ' Skipped';
                break;
            case 'I':
                $color  = 'fg-blue,bold';
                $buffer = $this->simpleOutput ? 'I' : $this->markers['incomplete']; // 'ℹ';
                $buffer .= !$this->debug ? '' : ' Incomplete';
                break;
            case 'F':
                $color  = 'fg-red,bold';
                $buffer = $this->simpleOutput ? 'F' : $this->markers['fail']; // mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE');
                $buffer .= (!$this->debug) ? '' : ' Fail';
                break;
            case 'E':
                $color  = 'fg-red,bold';
                $buffer = $this->simpleOutput ? 'E' : $this->markers['error']; // '⚈';
                $buffer .= !$this->debug ? '' : ' Error';
                break;
            case 'R':
                $color  = 'fg-magenta,bold';
                $buffer = $this->simpleOutput ? 'R' : $this->markers['risky']; // '⚙';
                $buffer .= !$this->debug ? '' : ' Risky';
                break;
        }

        echo parent::colorizeTextBox($color, $buffer);

        if ($this->debug) {
            $this->writeNewLine();
        }
        $this->column += 3;
    }
}
