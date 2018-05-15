<?php

class PrinterInit
{
    const GREEN = "\e[32m";
    const RESET = "\e[0m";
    const CYAN = "\e[36m";
    const RED = "\e[31m";
    const YELLOW = "\033[0;33m";

    public function __construct()
    {
    }

    public function init()
    {
        echo "\n==> Initializing phpunit-pretty-result-printer\n";
        echo "\n    " . self::CYAN ."[•  ] Initializing phpunit-pretty-result-printer\n" . self::RESET;
        $result = $this->add_printer_class_to_phpunit_xml();
        $result = $this->copy_default_settings();
        echo "\n==> Initializing Complete\n";
    }

    private function add_printer_class_to_phpunit_xml()
    {
        if (file_exists('./phpunit.xml')) {
            $data = file_get_contents('./phpunit.xml');
            $result = (int)strpos($data, 'printerClass=');
            if ($result > 0) {
                echo self::YELLOW . "    [•• ] Printer class already configured\n" . self::RESET;

                return 0;
            } else {
                $xml = simplexml_load_file('./phpunit.xml');
                $xml->addAttribute('printerClass', 'Codedungeon\PHPUnitPrettyResultPrinter\Printer');
                file_put_contents('./phpunit.xml', $xml->asXML());
                echo self::GREEN . "    [•• ] Printer class successfully added to " . self::CYAN  ."phpunit.xml" .self::GREEN . " file\n" . self::RESET;

                return 1;
            }
        } else {
            echo self::RED . "    [•• ] Unable to locate valid " . self::YELLOW . "./phpunit.xml" .self::RED ." file, you will need to set " . self::CYAN ."printerClass " .self::RED ."manually\n" . self::RESET;

            return -43;
        }
    }

    private function copy_default_settings()
    {
        $packageDefaultSettingFile = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'phpunit-printer.yml';
        $copySettingFile = './phpunit-printer.yml';
        if (file_exists($packageDefaultSettingFile)) {
            if (!file_exists($copySettingFile)) {
                copy($packageDefaultSettingFile, $copySettingFile);
                echo self::GREEN . "    [•••] Default " . self::CYAN ."phpunit-printer.yml" .self::GREEN ." copied to project root\n" . self::RESET;
            } else {
                echo self::GREEN . "    [•••] Default " . self::CYAN ."phpunit-printer.yml" .self::GREEN ." already exists\n" . self::RESET;
            }
        }
    }
}
