# php-result-printer Changelog

All notable changes to this project will be documented in this file.  
Apologies that this starts with version 0.27, but for some reason I did not have one before

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.28.0] - 2020-03-xx

### Added

- Added support for `symfony 5`
    * Updated `symfony/yaml` to included `^5.0` version
- Added support for `symfony/php-bridge`
- Removed internal `phpunit/phpunit` dependency to fix issue when using with `symfony`
- Updated `hassankhan/config` dependency to use latest version (2.1 as of this release)
- Added `--collision` flag to `init` method which will add the [Collision Listener](https://laravel-news.com/using-the-collision-phpunit-listener-with-laravel)
    * This flag should is only applicable working with Laravel Applications
    * If supplied but not using Laravel, it will be ignroed

### Information

- Addressed issues with PHPUnit 8.3.x and usage of `PHPUnit\TextUI\ResultPrinter` which was temporarily marked as Internal (changed to Interface in PHPUnit 9)
    * Decided to leave this deprecation notice as is, addressing the issue in `PHPUnit 8.5 or greater` will address the issue officially

## [0.27.0] - 2020-02-29

### Added

-   Added support for PHPUnit 9.0
-   Changed output character for skipped tests (`cd-skipped` flag in `phpunit-printer.yml`)
-   Refactored tests to check for custom template path for each command

## Credits

phpunit-result-printer written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Website: [https://github.com/mikeerickson](https://github.com/mikeerickson)

