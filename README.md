Yo
==

[Just Yo API](http://docs.justyo.co/v1.0/docs) wrapper for fun :)

[![Build Status](https://secure.travis-ci.org/toin0u/yo.png)](http://travis-ci.org/toin0u/yo)
[![Latest Stable Version](https://poser.pugx.org/toin0u/yo/v/stable.png)](https://packagist.org/packages/toin0u/yo)
[![Total Downloads](https://poser.pugx.org/toin0u/yo/downloads.png)](https://packagist.org/packages/toin0u/yo)


Installation
------------

This library can be found on [Packagist](https://packagist.org/packages/toin0u/yo).
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

```php
// ...

$yo->create('barbaz', 'newpassword');
```

Please note that you can provide a callback url, an email, a description and a boolean
to tell if the account requires location or not.

[Read more](http://docs.justyo.co/v1.0/docs/accounts)

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


Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/)


License
-------

This Yo package is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/yo/blob/master/LICENSE) file for details.
