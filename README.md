# CD PHPUnit Pretty Result Printer

Extend the default PHPUnit Result Printer with a modern, pretty printer!

[PHPUnit Pretty Result Printer -- Packagist](https://packagist.org/packages/codedungeon/phpunit-result-printer)

## Installation

Installation is provided via composer and can be done with the following command:

```bash
composer require --dev codedungeon/phpunit-result-printer
```

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

### License

Copyright &copy; 2017-2018 Mike Erickson
Released under the MIT license

### Credits

phpunit-result-printer written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Website: [codedungeon.org](http://codedungeon.org)

### Screenshot

![Screenshot](https://github.com/mikeerickson/phpunit-pretty-result-printer/blob/master/sample.png)
