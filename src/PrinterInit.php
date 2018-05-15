<?php

class PrinterInit
{
    const GREEN = "\e[32m";
    const RESET = "\e[0m";
    const CYAN = "\e[36m";
    const RED = "\e[31m";

    public function __construct()
    {

    }

    public function init()
    {
        $result = $this->add_printer_class_to_phpunit_xml();
        $result = $this->copy_default_settings();
    }

    private function add_printer_class_to_phpunit_xml()
    {
        if (file_exists('./phpunit.xml')) {
            $data = file_get_contents('./phpunit.xml');
            $result = (int)strpos($data, 'printerClass=');
            if ($result > 0) {
                echo self::CYAN . "Printer class already configured\n" . self::RESET;

                return 0;
            } else {
                $xml = simplexml_load_file('./phpunit.xml');
                $xml->addAttribute('printerClass', 'Codedungeon\PHPUnitPrettyResultPrinter\Printer');
                file_put_contents('./phpunit.xml', $xml->asXML());
                echo self::GREEN . "Printer class successfully added to `phpunit.xml` file\n" . self::RESET;

                return 1;
            }
        } else {
            echo self::RED . "Unable to locate valid `./phpunit.xml` file, you will need to update set `printerClass` manually\n" . self::RESET;

            return -43;
        }
    }

    private function copy_default_settings()
    {
        if (file_exists('phpunit-printer.yml')) {
            if (!file_exists('./.phpunit-printer.yml')) {
                copy('phpunit-printer.yml', './.phpunit-printer.yml');
                echo self::GREEN . "Default `phpunit-printer.yml` copied to application root\n" . self::RESET;
            }
        }
    }
}