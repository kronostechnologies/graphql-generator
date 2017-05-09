   Bugfixes:
- Check collision between Enums and Types (e.g. ContactTypeType).

Features:
- Remove additional uses from DTOs (remnants of complex DTOs)
- Add resolvers factory
- Empty resolvers should not be instantiated.
- `GraphQL\Type\Definition\Type` should not always be imported.
- Fix line spacing between class components (space between variables, functions, and their annotations).


Refactoring:

- Rework BaseTypeFormatter.
- Additional PSR4Writer options:
	- Disable resolvers generation.
	- Disable DTO generation.
- Improved unit testing structure: repositories.
- Improved error detection, exceptions thrown.
- Implement namespaceless generator.