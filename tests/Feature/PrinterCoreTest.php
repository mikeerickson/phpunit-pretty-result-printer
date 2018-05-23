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

    public function setUp()
    {
        $this->printer = new Printer();
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
        $this->assertTrue(true);
    }
}
