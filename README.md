# LaraParse

LaraParse provides a nice integration for using Parse (parse.com) with Laravel 5+.

Specifically, it

- Handles the registration and loading of the Parse SDK
- Gives you an auth provider you can use to login and register using Parse
- Provides a system for easily creating and registering subclasses, including an artisan generator and easy config

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
php artisan vendor:publish --provider=hipsterjazzbo/laraparse
```

## Usage

For general usage, you can just call the Parse SDK classes and methods like normal. See [ParsePlatform/parse-php-sdk](https://github.com/ParsePlatform/parse-php-sdk/pull/80/files) for more info.

```php
$query = new ParseQuery('Class');
$query->equalTo('key', 'value');
$object = $query->first();
```

### Subclasses

Subclasses can make Parse a lot easier to work with. They save you from always dealing with generic `ParseObjects`, and provide you with a place to add helper methods and even use docblocks to get column auto-completion in your IDE.

You can generate a subclass like so:

```bash
php artisan make:subclass ClassName
```

It is assumed that `ClassName` is the same as the class within Parse, but if not you can use the `--parse-class=ParseClass` option to set it manually.

The subclass will be created within `app/ParseClasses`.

You must then register the subclass in your `config/parse.php` file.

### Auth Provider

LaraParse provides a driver for Laravel's built-in auth system to work with Parse. To use it, simple go to your `config/auth.php` and update the `'driver'` key to `'parse'`

You may then use `Auth::attempt()` and friends as normal.

## Thanks

Thanks a lot to [@gfosco](https://github.com/gfosco) over at [ParsePlatform/parse-php-sdk](https://github.com/ParsePlatform/parse-php-sdk/pull/80/files) for helping deal with a few PRs that were neccessary for this package to be possible.