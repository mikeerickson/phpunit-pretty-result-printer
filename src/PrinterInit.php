<?php

class PrinterInit
{
    const GREEN   = "\e[32m";
    const RESET   = "\e[0m";
    const CYAN    = "\e[36m";
    const RED     = "\e[31m";
    const YELLOW  = "\033[0;33m";
    const LYELLOW = "\033[33;01m";
    const WHITE   = "\033[0;37m";
    const LWHITE  = "\033[37;01m";

    public function __construct()
    {
    }

    public function init($use_colors = 'never')
    {
        $phpunit_xml_file = './phpunit.xml';
        if (!file_exists($phpunit_xml_file)) {
            $phpunit_xml_file = './phpunit.xml.dist';
        }
        echo self::LWHITE . "\n==> Configuring phpunit-pretty-result-printer\n" . self::RESET;
        echo "\n    " . self::LWHITE . '[•  ]' . self::GREEN . " Gathering installation details\n" . self::RESET;
        $this->add_printer_class_to_phpunit_xml($phpunit_xml_file);
        $this->copy_default_settings('phpunit-printer.yml');
        echo self::LWHITE . "\n==> Configuration Complete\n" . self::RESET;
    }

    private function add_printer_class_to_phpunit_xml($file = './phpunit.xml')
    {
        $PHPUNIT_FILE = $file;
        if (file_exists($PHPUNIT_FILE)) {
            $data   = file_get_contents($PHPUNIT_FILE);
            $result = (int) strpos($data, 'printerClass=');
            if ($result > 0) {
                echo self::LWHITE . '    [•• ]' . self::LYELLOW . ' Printer class already configured in ' . self::CYAN . "{$PHPUNIT_FILE} \n" . self::RESET;

                return 0;
            } else {
                $xml = simplexml_load_file($PHPUNIT_FILE);
                $xml->addAttribute('printerClass', 'Codedungeon\PHPUnitPrettyResultPrinter\Printer');
                file_put_contents($PHPUNIT_FILE, $xml->asXML());
                echo self::LWHITE . '    [•• ]' . self::GREEN . ' Printer class successfully added to ' . self::CYAN . $PHPUNIT_FILE . self::GREEN . " file\n" . self::RESET;

                return 1;
            }
        } else {
            echo self::RED . '    [•• ] Unable to locate valid ' . self::YELLOW . $PHPUNIT_FILE . self::RED . ' file, you will need to set ' . self::CYAN . 'printerClass ' . self::RED . "manually\n" . self::RESET;

            return -43;
        }
    }

    private function copy_default_settings($file = 'phpunit-printer.yml')
    {
        $CONFIG_FILE               = $file;
        $packageDefaultSettingFile = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'src/'. $CONFIG_FILE;

        $copySettingFile = $CONFIG_FILE;
        if (file_exists($packageDefaultSettingFile)) {
            if (!file_exists($copySettingFile)) {
                copy($packageDefaultSettingFile, $copySettingFile);
                echo self::LWHITE . '    [•••]' . self::GREEN . ' Configuration ' . self::CYAN . './' . $CONFIG_FILE . self::GREEN . " copied to project root\n" . self::RESET;
            } else {
                echo self::LWHITE . '    [•••]' . self::LYELLOW . ' Configuration ' . self::CYAN . './' . $CONFIG_FILE . self::LYELLOW . " already exists\n" . self::RESET;
            }
        } else {
            echo self::LWHITE . '    [••E]' . self::RED . " An error occurred preparing configuration file\n" . self::RESET;
        }
    }
}
