A PHP package to show users how long it takes to read content.

<p align="center">
<img src="https://i.imgur.com/41dLat4.png">
</p>

## Installation

Install via composer:

```
composer require mtownsend/read-time
```

*This package is designed to work with any PHP 7.0+ application but has special support for Laravel.*

### Registering the service provider (Laravel)

For Laravel 5.4 and lower, add the following line to your ``config/app.php``:

```php
/*
 * Package Service Providers...
 */
Mtownsend\ReadTime\Providers\ReadTimeServiceProvider::class,
```

For Laravel 5.5 and greater, the package will auto register the provider for you.

### Using Lumen

To register the service provider, add the following line to ``app/bootstrap/app.php``:

```php
$app->register(Mtownsend\ReadTime\Providers\ReadTimeServiceProvider::class,);
```

### Publishing the config file (Laravel)

ReadTime has special configuration support for Laravel applications. You can publish a single config file and customize how you want your read time text to be displayed.

````
php artisan vendor:publish --provider="Mtownsend\ReadTime\Providers\ReadTimeServiceProvider" --tag="read-time-config"
````

These are the contents of the ``read-time.php`` config file:

```php
return [

    /*
     * Whether or not minute/second should be abbreviated as min/sec
     */
    'abbreviate_time_measurements' => false,

    /*
     * Omit seconds from being displayed in the read time estimate
     */
    'omit_seconds' => true,

    /*
     * Whether or not only the time should be displayed
     */
    'time_only' => false,

    /*
     * The average words per minute reading time
     */
    'words_per_minute' => 230,
];
```

### Publishing the translation files (Laravel)

ReadTime supports localization with Laravel. If you are using Laravel you'll likely want to use the premade translations.

````
php artisan vendor:publish --provider="Mtownsend\ReadTime\Providers\ReadTimeServiceProvider" --tag="read-time-language-files"
````

## Quick start

### Using the class

Here is an example of the most basic usage:

```php
use Mtownsend\ReadTime\ReadTime;

$readTime = (new ReadTime($content))->get();
```

You may also pass several arguments to the constructor if you wish to change settings on the fly:

```php
use Mtownsend\ReadTime\ReadTime;

$readTime = (new ReadTime($content, $omitSeconds = true, $abbreviated = false, $wordsPerMinute = 230))->get();
// or
$readTime = (new ReadTime($content))
				->omitSeconds()
				->abbreviated()
				->wpm($wordsPerMinute)
				->get();
```

The ReadTime class is able to accept a string of content or a flat array of multiple pieces of content. This may come in handy if you are attempting to display the total read time of body content along with sidebar content. For example:

```php
use Mtownsend\ReadTime\ReadTime;

$readTime = (new ReadTime([$content, $moreContent, $evenMoreContent]))->get();
```

## Methods, and arguments

**Method**

``->abbreviated(bool $abbreviated = true)``

Abbreviate the words 'minute' and 'second' to 'min' and 'sec'.

``->get()``

Retrieve the read time. *Note: you may also invoke the class as a function or cast it as a string to retrieve the same result as ``get()``.*

``->getTranslation($key = null)``

You may return the current translation array the class is using by omitting any argument from this method or get a specific translation key by passing it as an argument.

``->ltr(bool $ltr = true)``

Set the text direction of the read time result to left (default) with true, and ``right`` with ``false``. Alternatively, you may simply call the ``->rtl()`` method without any argument.

``->omitSeconds(bool $omitSeconds = true)``

Have the read time display omit seconds. Pass ``false`` to include seconds.

``->rtl(bool $rtl = true)``

Set the text direction of the read time result to right (default) with true, and ``left`` with ``false``. Alternatively, you may simply call the ``->ltr()`` method without any argument.

``->setTranslation(array $translations)``

Manually set the translation text for the class to use. If no key is passed it will default to the English counterpart. A complete translation array will contain the following:

```php
[
    'reads_left_to_right' => true,
    'min' => 'min',
    'minute' => 'minute',
    'sec' => 'sec',
    'second' => 'second',
    'read' => 'read'
]
```

``->timeOnly(bool $timeOnly = true)``

Omit any words from the read time result. Pass ``false`` to include words.

``->toArray()``

Get the contents and settings of the class as an array.

``->toJson()``

Get the contents and settings of the class as a json string.

``->wpm(int $wordsPerMinute)``

Set the average pace of words read per minute.

### Using the global helper (Laravel)

If you are using Laravel, this package provides a convenient helper function which is globally accessible.

```php
read_time($content);
```

The global helper is exceptionally useful in your Laravel application. It can be used in views (remember, it outputs the read time if invoked or cast as a string, which Blade's double curly braces does):

````
<h1>Some blog title</h1>
<small>{{ read_time($content) }}</small>
<hr>
````

The global helper will also attempt to intelligently detect the information you are passing it. For example, if you pass it a **non-associative array** it will assume you are passing an array of content.

````
<h1>Some blog title</h1>
<small>{{ read_time([$content, $moreContent]) }}</small>
<hr>
````

But you are still free to customize the ReadTime class settings on the fly using the global helper. Simply pass an associative array of settings:

````
{{ read_time([
    'content' => $content,
    // or
    // 'content' => [$content, $moreContent],
    'omit_seconds' => true,
    'time_only' => false,
    'abbreviated' => true,
    'words_per_minute' => 230,
    'ltr' => true,
    'translation' => [
        'reads_left_to_right' => true,
        'min' => 'min',
        'minute' => 'minute',
        'sec' => 'sec',
        'second' => 'second',
        'read' => 'read'
    ]
]) }}
````

## Purpose

Sites like Medium.com have popularized the concept of giving users an estimate for the amount of time it will take them to read the content. With this convenience in mind, ReadTime gives PHP developers the same tool for their readable content. It's a simple feature that will give a nice touch to your PHP application.

## Contributing translations

Pull requests for translations are encouraged. Please be sure to follow the existing format.

## Credits

- Mark Townsend
- [All Contributors](../../contributors)

## Testing

You can run the tests with:

```bash
./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
