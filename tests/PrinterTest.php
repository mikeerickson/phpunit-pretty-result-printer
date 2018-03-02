<?php

use Codedungeon\PHPUnitPrettyResultPrinter\Printer;

/**
 *  Corresponding Class to test Printer class.
 *
 *  @author mike erickson
 */
class PrinterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var
     */
    protected $printer;

    public function setUp()
    {
        $this->printer = new Printer();
    }

    public function testIsThereAnySyntaxError()
    {
        $this->assertInternalType('object', $this->printer);
    }

    /**
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     */
    public function testGetPackageName()
    {
        $this->assertSame('PHPUnit Pretty Result Printer', $this->printer->packageName());
    }

    /** @test  */
    public function should_return_full_pathname_to_config_file()
    {
        $this->assertContains('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }

    /** @test  */
    public function should_use_configuration_file()
    {
        $this->assertContains('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }
}
