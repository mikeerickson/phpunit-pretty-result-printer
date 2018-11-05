<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Runner\Version;
use PHPUnit_TextUI_ResultPrinter;

if (class_exists(PHPUnit_TextUI_ResultPrinter::class)) {
    class Printer extends ResultPrinter5
    {
        use PrinterTrait;
    }
}

if (version_compare(Version::series(), '6.99.99', '<=')) {
    class Printer extends ResultPrinter6
    {
        use PrinterTrait;
    }
}

$low  = version_compare(Version::series(), '7.0', '>=');
$high = version_compare(Version::series(), '7.0.99', '<=');

if ($low && $high) {
    class Printer extends ResultPrinter70
    {
        use PrinterTrait;
    }
}

$low  = version_compare(Version::series(), '7.1', '>=');
$high = true; // version_compare(Version::series(),'7.1.99','<=');

if ($low && $high) {
    class Printer extends ResultPrinter71
    {
        use PrinterTrait;
    }
}
