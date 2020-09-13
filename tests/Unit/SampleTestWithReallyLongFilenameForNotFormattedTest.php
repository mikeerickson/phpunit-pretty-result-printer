<?php

use Codedungeon\PHPUnitPrettyResultPrinter\Printer;

/**
 *  Corresponding Class to test Printer class.
 *
 * @author mike erickson
 */
class SampleTestWithReallyLongFilenameForNotFormattedTest extends PHPUnit\Framework\TestCase
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
    public function it_should_pass_test_part_of_long_filename(): void
    {
        $this->assertTrue(true);
    }
}
