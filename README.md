# graphql-generator

[![CircleCI](https://circleci.com/gh/kronostechnologies/graphql-generator.svg?style=svg)](https://circleci.com/gh/kronostechnologies/graphql-generator)
[![Coverage Status](https://coveralls.io/repos/github/kronostechnologies/graphql-generator/badge.svg?branch=master)](https://coveralls.io/github/kronostechnologies/graphql-generator?branch=master)

Converts a GraphQL Schema to static PHP files. It currently supports generating namespaced class file. The tool is interfaced through a CLI.

## Requirements

- **PHP 5.6** or higher.
- Composer


## Installation

The repository can be cloned in order to gain access to the GraphQL generator.

```
git clone https://github.com/kronostechnologies/graphql-generator.git
composer install
```


## Examples

The [examples](./examples) source directory contains some graphqls schema examples on which you can run the tool against.

### Simple schema generation

To generate class files from a valid graphqls file, run the following command:

```
php graphqlgen generate-classes ./examples/base.graphqls ./base
```

The output files will appear in the `base` directory.


## Testing

Run unit tests with PHPUnit 5.7.

```
phpunit
```


## Usage

You can run the CLI tool with the following command:

```
php graphqlgen generate-classes [options] [--] <input> <targetdir>
```

Arguments are:
* _input_: A .graphqls file to use as input.
* _targetdir_: A directory in which to generate the classes.

Available options are:
* `--writer=WRITER`: Type of writer with which to output the files. Default is namespaced.
    * Namespaced Writer: A class will be generated for each type definition under `Types\Enums` for enum types, `Types\Input` for input types, `Types\Interfaces` for interface types, `Types\Scalars` for scalar types, `Types\Unions` for union types, and `Types` for standard types. Per-directory namespacing standard will be respected.
* `--stubs-dir`: This is a directory containing the stub files.
* `--overwrite`: If this flag is set, the writer will attempt to override the files. If not, a warning will be thrown when an existing file is found.
* `--formatter-use-tabs`: If this flag is set, the PHP ouput file formatter will use tabs instead of spaces for indentation.
* `--formatter-indent-spaces`: If not using tabs for the formatter, this is the number of spaces per indent block. Default is 4.
* `--formatter-line-merge`: When descriptions are written on multiple lines in a .graphqls file, they are merged with the specified character. Default is `,`.

### Namespaced Writer

Although the namespaced writer is currently the only writer supported, it comes with a few options bundled:

* `--namespaced-target-namespace`: If using the namespaced Writer, the given namespace will be prefixed. This does not alter the target directory structure.

#### Additional files

In addition to generating the type definitions for each type, the following files are generated.

All types are statically initialized in the *TypeStore*. This helps ensure only a single instance of a type definition throughout the application. The properties of each type are stored independently in a *DTO*.

TypeStore location:
```
\TypeStore
```

For *input* types, and *Union* types, a *resolver* is created. This file is intended to be editable by the user of this tool, and to split the type definition from the actual code. The resolvers are initialized through the `ResolverFactory`. This allows passing external values to the resolvers constructors.

Resolvers namespace format:
```
Resolvers\Types\[TypeCategory]\TypeName
```

Additionally, for *input* types, a *DTO* is created. DTO serve as a data structure that should ultimately be returned by the resolver functions. It is important to separate these concerns as a type definition can only exist once (hence the need of a TypeStore), but a DTO can exist multiple times throughout the application.

DTO namespace format:
```
DTO\Types\[TypeCategory]\TypeName
```

*Interfaces* use composition instead of inheritance due to GraphQL being able to implement multiple of them. They are declared in two components: a trait, and a bare class implementing this trait. The trait contains all members. 

#### Stub Files

The stub files are used as a boilerplate for generating the files of a specific GraphQL type. For the namespaced Writer, the files required are the same ones in [src/Generator/Writer/Namespaced/stubs](./src/Generator/Writer/Namespaced/stubs).

For example, an interface type will use `interface.stub` as its base file for generating the class. The following types are matched:

* Enums: `enum.stub`
* Interfaces: `interface.stub`
* Inputs: `input.stub`
* Object: `object.stub`
* Scalar: `scalar.stub`
* Unions: `union.stub`

Additionally, `typestore.stub` is matched to the `TypeStore`, `dto.stub` to DTOs, and `resolver.stub` to resolvers.

See [src/Generator/Writer/Namespaced/stubs](./src/Generator/Writer/Namespaced/stubs) for the stub files content.

## Mutations

Mutations are currently ignored when generating the GraphQL files. As these mostly imply logical operations, you will need to implement the resolve functions yourself.

