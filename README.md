# inMobile library for Laravel

[inMobile](https://inmobile.dk) library for Laravel.

**Note**: Do not use this library on production. It's still under development


## Installation

    composer require juanparati/inmobile

Facade registration (optional)

    'aliases' => [
        ...
        'InMobile' => \Juanparati\Inmobile\Facades\InmobileFacade::class,
        ...
    ]


## Configuration

Publish configuration file:

    artisan vendor:publish --tag="inmobile"

## Usage

### List service

#### Create list

```php 
InMobile::lists()->create('My list');
```

#### Get all lists

```php
// Return a paginated results instance.
$lists = InMobile::lists()->all();

// Will automatically transverse all the pages automatically.
// Rewind is not allowed.
foreach ($lists as $list)
    var_dump($list->toArray());
```

#### Get a list

```php
InMobile::lists()->find($myListId);
```

#### Create a list

```php
InMobile::lists()->create('My new list');
```

### Recipient service

Pending to document....

