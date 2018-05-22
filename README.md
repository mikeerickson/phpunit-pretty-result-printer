# CD PHPUnit Pretty Result Printer

Extend the default PHPUnit Result Printer with a modern, pretty printer!

[PHPUnit Pretty Result Printer -- Packagist](https://packagist.org/packages/codedungeon/phpunit-result-printer)

## Installation

Installation is provided via composer and can be done with the following command, the current version requires PHP 7.1 or greater:

```bash
$ composer require --dev codedungeon/phpunit-result-printer
```

### Execute Initialization Script (Optional)
The following steps are optional, but will provide zero configuration for implementing **phpunit-pretty-result-printer**

- Adds `printerClass="Codedungeon\PHPUnitPrettyResultPrinter\Printer"` to `phpunit.xml` file
- Copies default `phpunit-printer.xml` to project root for easier customization


```bash
$ php ./vendor/codedungeon/phpunit-result-printer/init.php
```

#### Manual Configuration
Alternately, if you wish to configure **phpunit-pretty-result-printer** manually, you will need to update your `phpunit.xml` file as follows

```xml
<?xml version="1.0" encoding="UTF-8"?>
  <phpunit printerClass="Codedungeon\PHPUnitPrettyResultPrinter\Printer">
    // ....
  </phpunit>
```

Or from Command-Line:

```bash
$ phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer
```

### Laravel 5.3 with PHP version 7.0.x

If you are using PHP 7.0.x, you will need to use a compatible version of PHPUnit Result Printer (version 0.8.x)

```bash
$ composer require --dev codedungeon/phpunit-result-printer:^0.8
```

### Configuration Options

* Create a `phpunit-printer.yml` file in your application root to override default (or anywhere use up the parent tree. It will search recursively up the tree until a configuration file is found. If not found, default configuration will be used).
The following options are available (along with their default values):

#### Options ####
---|---|----
**Property Name** | **Default** | **Description**
`cd-printer-hide-class` | false | Hides the display of the test class name
`cd-printer-simple-output`| false | Uses the default PHPUnit markers (but still uses Printer)
`cd-printer-show-config`| true | Show path to used configuration file
`cd-printer-hide-namespace`| false |Hide test class namespaces (will only show print class name)

#### Markers ###
You can customize the markers which are used for `success`, `fail`, `error`, `skipped`, `incomplete` by modifying the `phpunit-printer.yml` file.

---|---
**Marker** | **Value** *
 
  cd-pass | "✓ "
  cd-fail | "✖ "
  cd-error | "⚈ "
  cd-skipped | "→ "
  cd-incomplete | "∅ "
  cd-risky | "⌽ "

_* Notice space after each marker.  This makes the output a little more visually appealing, thus keep that in mind when creating your own custom markers_

## License

Copyright &copy; 2017-2018 Mike Erickson
Released under the MIT license

## Credits

phpunit-result-printer written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Website: [https://github.com/mikeerickson](https://github.com/mikeerickson)

### Screenshot

![Screenshot](https://raw.githubusercontent.com/mikeerickson/phpunit-pretty-result-printer/master/sample.png)
