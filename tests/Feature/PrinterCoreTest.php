<?php

use Codedungeon\PHPUnitPrettyResultPrinter\Printer;

/**
 *  Corresponding Class to test Printer class.
 *
 * @author mike erickson
 */
class PrinterCoreTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var
     */
    protected $printer;

    public function setUp(): void
    {
        $this->printer = new Printer();
    }

    /** @test  */
    public function it_should_pass(): void
    {
        $this->assertTrue(true);
    }

    /** @test  */
    public function should_produce_syntax_error()
    {
        $this->pass();
    }

    /** @test  */
    public function it_should_fail(): void
    {
        $this->assertTrue(false);
    }

    /** @test */
    public function it_should_display_incomplete(): void
    {
        $this->markTestIncomplete('Incomplete Test');
        $this->assertTrue(true);
    }

    /** @test */
    public function it_should_display_skipped(): void
    {
        $this->markTestSkipped('Skipped Test');
        $this->assertTrue(true);
    }

    /** @test */
    public function it_should_display_risky(): void
    {
        $this->markAsRisky('Risky Test');
    }
}
