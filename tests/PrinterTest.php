<?php

/**
 *  Corresponding Class to test Printer class
 *
 *  @author mike erickson
 */
class PrinterTest extends PHPUnit\Framework\TestCase
{
    public function testIsThereAnySyntaxError()
    {
        $var = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
        $this->assertInternalType('object', $var);
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
        $this->assertSame('PHPUnit Pretty Result Printer', $printer->packageName());
        unset($var);
    }

    /** @test  */
    public function should_return_full_pathname_to_config_file()
    {
        // create test file
       $printer = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
       $this->assertContains('phpunit-printer.yml', $printer->getConfigurationFile());
       $this->assertFileExists($printer->getConfigurationFile());
    }

    /** @test  */
    public function should_use_configuration_file()
    {
        // create test file
       $printer = new Codedungeon\PHPUnitPrettyResultPrinter\Printer;
       $this->assertContains('phpunit-printer.yml', $printer->getConfigurationFile());
       $this->assertFileExists($printer->getConfigurationFile());
    }

}
