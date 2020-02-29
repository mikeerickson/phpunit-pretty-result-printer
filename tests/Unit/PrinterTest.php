<?php

use Codedungeon\PHPUnitPrettyResultPrinter\Printer;

/**
 *  Corresponding Class to test Printer class.
 *
 * @author mike erickson
 */
class PrinterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var
     */
    protected $printer;

    public function setUp(): void
    {
        $this->printer = new Printer();
    }

    /** @test */
    public function should_return_module_package_name(): void
    {
        $this->assertSame('PHPUnit Pretty Result Printer', $this->printer->packageName());
    }

    /** @test */
    public function should_return_full_pathname_to_config_file(): void
    {
        $this->assertStringContainsString('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }

    /** @skip */
    public function should_use_configuration_file(): void
    {
        $this->assertStringContainsString('phpunit-printer.yml', $this->printer->getConfigurationFile());
        $this->assertFileExists($this->printer->getConfigurationFile());
    }

    /** @skip  */
    public function should_throw_an_error()
    {
        // this is toggled to assure failure testing and reporting
        $this->assertTrue(true);
    }

    /** @skip  */
    public function should_fail()
    {
        $this->assertTrue(false);
    }

    /** @skip  */
    public function should_fail_again()
    {
        $this->assertTrue(false);
    }

    /** @skip  */
    public function should_skip()
    {
        $this->markTestSkipped();
    }

    /** @skip  */
    public function should_skip_another()
    {
        $this->markTestSkipped();
    }

    /** @skip  */
    public function should_be_incomplete()
    {
        $this->markTestIncomplete();
    }

    /** @skip  */
    public function should_be_risky()
    {
        $this->markAsRisky();
    }
}
