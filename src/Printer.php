<?php
namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit\Runner\Version;

$low  = version_compare(Version::series(), '9.0', '>=');
$high = true; // version_compare(Version::series(),'8.99.99','<=');

if ($low && $high) {
    class Printer extends ResultPrinter90
    {
        use PrinterTrait8;  // new trait introduced for PHP 8.x
    }
}
