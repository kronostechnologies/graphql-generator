# graphql-generator

[![CircleCI](https://circleci.com/gh/kronostechnologies/graphql-generator.svg?style=svg)](https://circleci.com/gh/kronostechnologies/graphql-generator)
[![Coverage Status](https://coveralls.io/repos/github/kronostechnologies/graphql-generator/badge.svg?branch=master)](https://coveralls.io/github/kronostechnologies/graphql-generator?branch=master)

This is a GraphQL schema generator with a CLI interface available. Currently in WIP.

## Requirements

- **PHP 5.6** or higher.
- Composer

## Installation

As this is currently in development, you can clone this repository and try out the CLI tool with composer.

```
git clone https://github.com/kronostechnologies/graphql-generator.git
composer install
```

## Testing

Run unit tests with PHPUnit 5.7.

```
phpunit
```

## Usage

The CLI tool can be ran as such:

```
php graphqlgen --help
```



### Classes generator

```
  php graphqlgen generate-classes [options] [--] <input> <targetdir>
```

Arguments are:
* _input_: A .graphqls file to use as input.
* _targetdir_: A directory in which to generate the classes.

Available options are:
* `--writer=WRITER`: Type of writer with which to output the files. Default is psr4.
    * PSR4 Writer: A class will be generated for each type definition under `Types\Enums` for enum types, `Types\Interfaces` for interface types, `Types\Scalars` for scalar types, and `Types` for standard types. The PSR-4 standard will be respected.
* `--psr4-namespace`: If using the PSR4 Writer, the given namespace will be prefixed. This does not alter the target directory structure.
* `--psr4-stubs-path`: If using the PSR4 Writer, this is a directory containing the stub files for generating classes. The files required are the same ones in [src/Generator/Writer/PSR4/stubs](./src/Generator/Writer/PSR4/stubs)
* `--overwrite`: If this flag is set, the writer will attempt to override the files. If not, a warning will be thrown when an existing file is found.
* `--formatter-use-tabs`: If this flag is set, the PHP ouput file formatter will use tabs instead of spaces for indentation.
* `--formatter-indent-spaces`: If not using tabs for the formatter, this is the number of spaces per indent block. Default is 4.
* `--formatter-line-merge`: When descriptions are written on multiple lines in a .graphqls file, they are merged with the specified character. Default is `,`.
