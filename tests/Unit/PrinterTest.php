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
    public function should_not_have_any_syntax_errors(): void
    {
        $this->assertInternalType('object', $this->printer);
    }

    /** @test  */
    public function should_return_module_package_name(): void
    {
        $this->assertSame('PHPUnit Pretty Result Printer', $this->printer->packageName());
    }

    /** @test  */
    public function should_return_full_pathname_to_config_file(): void
    {
        $this->assertContains('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }

    /** @test  */
    public function should_use_configuration_file(): void
    {
        $this->assertContains('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }

    public function it_should_display_incomplete(): void
    {
        $this->markTestIncomplete('Incomplete Test');
        $this->assertTrue(true);
    }

    public function it_should_display_skipped(): void
    {
        $this->markTestSkipped('Skipped Test');
        $this->assertTrue(true);
    }
}
