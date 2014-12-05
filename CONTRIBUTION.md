CONTRIBUTING
============

Contributions are **welcome** and be fully **credited** <3

This library will use the [PSR2 Coding Standard](http://www.php-fig.org/psr/psr-2/).
The easiest way to apply these conventions is to install [PHP_CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer).

You may be interested in [PHP Coding Standards Fixer](https://github.com/fabpot/PHP-CS-Fixer).

Installation
------------

``` bash
$ pear install PHP_CodeSniffer
$ # Or
$ php composer.phar global require "squizlabs/php_codesniffer=*"
$ phpcs --config-set default_standard PSR2
```

Usage
-----

``` bash
$ phpcs src/
```

**Happy coding** !
