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

## Usage examples

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

#### Create recipient

```php
$recipient = InMobile::recipients()->create(
    'listId', 
    \Juanparati\Inmobile\Models\Recipient::make('45', '12345678')
        ->addField('firstname', 'John')
        ->addField('lastname', 'Random')
        ->addField('custom1', 'foobar')
        ->setCreatedAt(now()->subMinute()
    )
);

echo 'Recipient id: ' . $recipient->getId();
```

#### Find recipient by Id

```php
if ($recipient = InMobile::recipients()->findById('listid', 'recipientId')) {
    echo 'Recipient ' . $recipient->getId() . ' has phone +' . $recipient->getCode() . ' ' . $recipient->getPhone(); 
    var_dump($recipient->toArray());
} else {
    echo 'Recipient not found';
}
```

#### Find recipient by Phone

```php
$recipient = InMobile::recipients()->findByNumber('listid', '45', '12345678');
```
