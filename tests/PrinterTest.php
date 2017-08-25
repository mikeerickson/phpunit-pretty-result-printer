<?php

/**
 *  Corresponding Class to test Printer class
 *
 *  For each class in your library, there should be a corresponding Unit-Test for it
 *  Unit-Tests should be as much as possible independent from other test going on.
 *
 *  @author mike erickson
 */
class PrinterTest extends PHPUnit_Framework_TestCase
{
    public function testIsThereAnySyntaxError()
    {
        $var = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
        $this->assertTrue(is_object($var));
        unset($var);
    }

    /**
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testGetPackageName()
    {
        $printer = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
        $this->assertTrue($printer->packageName() == 'PHPUnit Pretty Result Printer');
        unset($var);
    }

    /** @test  */
    public function should_return_full_pathname_to_config_file()
    {
        // create test file
       $printer = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
       $this->assertContains('phpunit-printer.yml', $printer->getConfigurationFile());
       $this->assertTrue(file_exists($printer->getConfigurationFile()));
    }

    /** @test  */
    public function should_display_configuration_file_used_by_printer()
    {
        // create test file
       $printer = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
       $this->assertContains('phpunit-printer.yml', $printer->getConfigurationFile());
       $this->assertTrue(file_exists($printer->getConfigurationFile()));
    }

}
