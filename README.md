# LaraParse

LaraParse provides a nice integration for using Parse (parse.com) with Laravel 5+.

Specifically, it

- Handles the registration and loading of the Parse SDK
- Gives you an auth provider you can use to login and register using Parse
- Provides a system for easily creating and registering subclasses, including an artisan generator and easy config
- Provides generators and base classes for repositories

Future plans include

- Automatic generation of subclasses*
- Docblock generation for subclasses*

\* *Depends on a schema API being released by Parse*

## Installation

First, include LaraParse in your `composer.json`:

```bash
composer require hipsterjazzbo/laraparse
```

Then load the service provider in your `config/app.php`:

```php
'LaraParse\ParseServiceProvider'
```

You'll also need to publish the config, so you can provide your keys:

```bash
php artisan vendor:publish  --provider="LaraParse\ParseServiceProvider" --tag="config"
```

## Usage

For general usage, you can just call the Parse SDK classes and methods like normal. See [ParsePlatform/parse-php-sdk](https://github.com/ParsePlatform/parse-php-sdk) for more info.

```php
$query = new ParseQuery('Class');
$query->equalTo('key', 'value');
$object = $query->first();
```

### Auth Provider

LaraParse provides a driver for Laravel's built-in auth system to work with Parse. To use it, simply go to your `config/auth.php` and update the `'driver'` key to `'parse'`

You may then use `Auth::attempt()` and friends as normal.

### Subclasses

Subclasses can make Parse a lot easier to work with. They save you from always dealing with generic `ParseObjects`, and provide you with a place to add helper methods and even use docblocks to get column auto-completion in your IDE.

You can generate a subclass like so:

```bash
php artisan parse:subclass ClassName
```

It is assumed that `ClassName` is the same as the class within Parse, but if not you can use the `--parse-class=ParseClass` option to set it manually.

The subclass will be created within `app/ParseClasses`.

You must then register the subclass in your `config/parse.php` file.

**Note:** If you'd like to subclass the Parse `User` class, you should extend `LaraParse\Subclasses\User`, to ensure the Auth driver will still work.

#### Casting

Generated subclasses use the `\LaraParse\Traits\CastsParseProperties` trait, which tries to help you out a bit. It will:

- Change all `Date` columns into `\Carbon\Carbon` instances
- Allow you to access built-in columns as properties (for instance, `$class->objectId` instead of `$class->getObjectId()`)
- Allow you to specify a method on your subclass with the same name as a Parse column, that will be called when accessing that column.

### Repositories

LaraParse includes a few commands and base classes to assist with setting up repositories to use with Parse.

To generate a new repository, you can use the artisan command:

```bash
php artisan parse:repository ClassName
```

Like subclasses, it is assumed that `ClassName` is the same as the class within Parse, but if not you can use the `--parse-class=ParseClass` option to set it manually.

By default, this command will generate both a contract and an implementation that extends an abstract base class, providing a full-featured repository that's ready to go.

If you'd rather just generate an implementation, you can use `--which="implementation"`.

See `\LaraParse\Repositories\Contracts\ParseRepository` to learn what methods are available.

If you want to bind the implementation to the contract you can populate the repositories array in the parse.php config (http://laravel.com/docs/5.0/container#binding-interfaces-to-implementations)

#### Using master key

If you need to use the master key for a query, you can do it like so:

```php
$repository = new ClassRepository();
$repository->userMasterKey(true)->all();
```

## Thanks

Thanks a lot to [@gfosco](https://github.com/gfosco) over at [ParsePlatform/parse-php-sdk](https://github.com/ParsePlatform/parse-php-sdk) for helping deal with a few PRs that were neccessary for this package to be possible.
