## Change Log

### [Unreleased][unreleased]

### [1.3.0] - 2024-10-22
#### Added
- PHP8.3 support
- Symfony 7 support

### [1.2.0] - 2023-08-29
#### Changed
- Do not throw exception when max retries is reached
- Replaced abandoned fzaninotto/faker with fakerphp/faker

#### Added
- Numeric data type
- Ignore and skip tables in automatic populator command
- Add test combination foreign key and self foreign key to test class table analyzer
- Support for PHP 8.0, PHP 8.1 and PHP 8.2
- Support for symfony/console:6.x

#### Fixed
- Progress bar

### [1.1.0] - 2021-05-26
#### Added
- Automatic populator command based on database structure
- Column name + type custom classes for AutomaticPopulator
- New data types: timestamp, json

### [1.0.0] - 2019-04-01
#### First stable version
- Populator commands
- Database structure resolver
- Data type populators

[unreleased]: https://github.com/lulco/populator/compare/1.3.0...HEAD
[1.3.0]: https://github.com/lulco/populator/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/lulco/populator/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/lulco/populator/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/lulco/populator/compare/f744a241c8cb78327e2d5d382f5af88228779cfb...1.0.0
