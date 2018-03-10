# Hydrator 
[![Coverage Status](https://coveralls.io/repos/github/Grzesie2k/Hydrator/badge.svg?branch=master)](https://coveralls.io/github/Grzesie2k/Hydrator?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/7477160d779ec13e4dd0/maintainability)](https://codeclimate.com/github/Grzesie2k/Hydrator/maintainability)
## Installation
```bash
composer install grzesie2k/hydrator
```

## Examples
* Validate primitive type
```php
<?php

$hydrator = $hydratorFactory->createHydrator('int[]'); // any valid type

$intList = $hydrator->hydrator([1, 2, 3]); // ✓ OK
$intList = $hydrator->hydrator([1, 'a', 2]); // ☹ exception
```
* Hydrate Your class by constructor
```php
<?php

class Example
{
    /**
     * @param int $id
     * @param string $name we can read types from PHPDoc
     */
    public function __construct(int $id, $name) // or from type hints
    {
        // ... some operation
    }
}

$hydrator = $hydratorFactory->createHydrator(Example::class);

$hydrator->hydrate(\json_decode('{"id":2,"name":"Adam"}')); // ✓ OK
$hydrator->hydrate(\json_decode('{"id":"Nope","name":"Janek"}')); // ☹ exception
```

## To do
* create hydrator strategy to handle compound type (eq. int|string)
* create alternative object hydration strategy (without constructor)
* create hydrator strategies for some built-in types (eg. DateTime)
* handle hydration exceptions with internal class and better messages
* create symfony/laravel bundle
* add examples and better docs
