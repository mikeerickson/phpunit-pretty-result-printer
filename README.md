# CD PHPUnit Pretty Result Printer

Extend the default PHPUnit Result Printer with a modern, pretty printer!

[PHPUnit Pretty Result Printer -- Packagist](https://packagist.org/packages/codedungeon/phpunit-result-printer)

### Installation

Installation is provided via composer and can be done with the following command, the current version requires PHP 7.1 or greater:

```bash
composer require --dev codedungeon/phpunit-result-printer
```
**Laravel 5.3 with PHP version 7.0.x:**

If you are using PHP 7.0.x, you will need to use a compatible version of PHPUnit Result Printer (version 0.8.x)

```bash
composer require --dev codedungeon/phpunit-result-printer:^0.8
```

### Usage

To activate the Printer for PHPUnit, just add it to your configuration XML:

  ```xml
  <?xml version="1.0" encoding="UTF-8"?>
    <phpunit printerClass="Codedungeon\PHPUnitPrettyResultPrinter\Printer">
      // ....
    </phpunit>
  ```

Or from Command-Line:

  ```bash
  phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer
  ```

#### Customizing Markers
You can customize the markers which are used for `success`, `fail`, `error`, `skipped`, `incomplete` by modifying the `phpunit-printer.yml` file.
- Create a `phpunit-printer.yml` file in your application root to override default

The following are the default markers used (from the package `phpunit-printer.yml` file)
```
markers:
  cd-pass: "✓"
  cd-fail: "✖"
  cd-error: "⚈"
  cd-skipped: "→"
  cd-incomplete: "∅ "
```

### License

Copyright &copy; 2017-2018 Mike Erickson
Released under the MIT license

### Credits

phpunit-result-printer written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Website: [https://github.com/mikeerickson](https://github.com/mikeerickson)
### Screenshot

![Screenshot](https://github.com/mikeerickson/phpunit-pretty-result-printer/blob/master/sample.png)
