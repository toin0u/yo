Yo
==

[Just Yo API](http://docs.justyo.co/v1.0/docs) wrapper for fun :)

[![Build Status](https://secure.travis-ci.org/toin0u/yo.png)](http://travis-ci.org/toin0u/yo)
[![Latest Stable Version](https://poser.pugx.org/toin0u/yo/v/stable.png)](https://packagist.org/packages/toin0u/yo)
[![Total Downloads](https://poser.pugx.org/toin0u/yo/downloads.png)](https://packagist.org/packages/toin0u/yo)


Installation
------------

This library can be found on [Packagist](https://packagist.org/packages/toin0u/NAME).
The recommended way to install this is through [composer](http://getcomposer.org).

Run these commands to install composer, the library and its dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require toin0u/yo:@stable
```

Or edit `composer.json` and add:

```json
{
    "require": {
        "toin0u/yo": "@stable"
    }
}
```

**Protip:** you should browse the
[`toin0u/yo`](https://packagist.org/packages/toin0u/yo)
page to choose a stable version to use, avoid the `@stable` meta constraint.


API
---

This package uses the **awesome** [Ivory Http Adapter](https://github.com/egeloen/ivory-http-adapter) which supports
plenty [adapters](https://github.com/egeloen/ivory-http-adapter/blob/master/doc/adapters.md). We will use the
`CurlHttpAdapter` for our examples.

Following methods throw a `\RuntimeException` if something goes wrong during the API calls.

```php
<?php

require 'vendor/autoload.php';

use Ivory\HttpAdapter\CurlHttpAdapter;
use Yo\Yo;

$yo = new Yo(new CurlHttpAdapter, 'you_api_token');

// ...
```

You can find your API token in your [Yo dashboard](http://dev.justyo.co/).

Following API examples will use the `Yo` instance created previously:

### /yo/ ###

```php
// ...

$yo->user('foobar'); // Yo FOOBAR
$yo->user('foobar', new \Yo\Bag\Link('http://sbin.dk/')); // Yo FOOBAR with a link
$yo->user('foobar', new \Yo\Bag\Location(55.699953, 12.552736)); // Yo FOOBAR with a location
```

This method returns `true` on success `false` otherwise.

[Read more](http://docs.justyo.co/v1.0/docs/yo)

### /yoall/ ###

```php
// ...

$yo->all(); // Yo your subscribers
$yo->all(new \Yo\Bag\Link('http://sbin.dk/')); // Yo your subscribers with a link
```

This method returns nothing. Please note The API allows only one Yo once per minute.

[Read more](http://docs.justyo.co/v1.0/docs/yoall)

### /accounts/ ###

Not implemented yet. Coming soon.

### /check_username/ ###

```php
// ...

$yo->exists('foobar'); // checks if FOOBAR exsists or not.
```

This method returns `true` on success `false` otherwise.

[Read more](http://docs.justyo.co/v1.0/docs/check_username)

### /subscribers_count/ ###

```php
// ...

echo $yo->total(); // get total number of subscribers
```

[Read more](http://docs.justyo.co/v1.0/docs/subscribers_count)


Specification tests
-------------------

Install [PHPSpec](http://www.phpspec.net/) [globally](https://getcomposer.org/doc/00-intro.md#globally)
with composer and run it in the project.

```bash
$ composer global require phpspec/phpspec:@stable
$ phpspec run -fpretty
```


Contributing
------------

Please see [CONTRIBUTING](https://github.com/toin0u/yo/blob/master/CONTRIBUTING.md) for details.


Support
-------

[Please open an issues in github](https://github.com/toin0u/yo/issues)


License
-------

This Yo package is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/yo/blob/master/LICENSE) file for details.
