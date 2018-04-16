<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

use PHPUnit_TextUI_ResultPrinter;

if (class_exists(PHPUnit_TextUI_ResultPrinter::class)) {
    require __DIR__ . '/ResultPrinter5.php';

    class Printer extends ResultPrinter5
    {
        use PrinterTrait;
    }
}

use PHPUnit\Runner\Version;

if (version_compare(Version::series(), '6.99.99', '<=')) {
    require __DIR__ . '/ResultPrinter6.php';

    class Printer extends ResultPrinter6
    {
        use PrinterTrait;
    }
}

$low = version_compare(Version::series(), '7.0', '>=');
$high = version_compare(Version::series(), '7.0.99', '<=');

if ($low && $high) {
    require __DIR__ . '/ResultPrinter70.php';

    class Printer extends ResultPrinter70
    {
        use PrinterTrait;
    }
}

$low = version_compare(Version::series(), '7.1', '>=');
$high = true; // version_compare(Version::series(),'7.1.99','<=');

if ($low && $high) {
    require __DIR__ . '/ResultPrinter71.php';

    class Printer extends ResultPrinter70
    {
        use PrinterTrait;
    }
}
