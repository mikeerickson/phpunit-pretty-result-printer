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

    /** @test  */
    public function should_not_have_any_syntax_errors()
    {
        $this->assertInternalType('object', $this->printer);
    }

    /** @test  */
    public function should_return_module_package_name()
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

    /** @test  */
    public function it_should_display_incomplete()
    {
        $this->markTestIncomplete('Incomplete Test');
        $this->assertTrue(true);
    }

    /** @test  */
    public function it_should_display_skipped()
    {
        $this->markTestSkipped('Skipped Test');
        $this->assertTrue(true);
    }
}
