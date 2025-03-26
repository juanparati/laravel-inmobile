# InMobile library for Laravel

[InMobile](https://inmobile.dk) library for Laravel.


## Installation

    composer require juanparati/inmobile

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


#### Get all recipients in list

```php
$recipients = InMobile::lists()->getRecipients($myListId);

// Will automatically transverse all the pages automatically.
// Rewind is not allowed.
foreach ($recipients as $recipient)
    var_dump($recipient->toArray());
```

#### Other methods
- update
- delete
- truncate

### Recipient service

#### Create recipient

```php
$recipient = InMobile::recipients()->create(
    $listId, 
    \Juanparati\InMobile\Models\Recipient::make('45', '12345678')
        ->addField('firstname', 'John')
        ->addField('lastname', 'Random')
        ->addField('custom1', 'foobar')
        ->setCreatedAt(now()->subMinute())
);

echo 'Recipient id: ' . $recipient->getId();
```

#### Update recipient

```php

// Only update some fields
InMobile::recipients()->updateById(
    $listId,
    $recipientId, 
    \Juanparati\InMobile\Models\Recipient::make()
        ->addField('firstname', 'John')
        ->addField('lastname', 'Random')
        ->addField('custom1', 'foobar'),       
    false   // Indicate that only values set are updated (Default)
);

// Full update
InMobile::recipients()->updateById(
    $listId,
    $recipientId, 
    \Juanparati\InMobile\Models\Recipient::make('35', '12345678')
        ->addField('firstname', 'John')
        ->addField('lastname', 'Random')
        ->addField('custom1', 'foobar')
        ->setCreatedAt(now())
        ,       
    true   // Indicate that all fields are update, the fields missing in the recipient model are emptied
);

// or use updateByNumber instead (It's slower than updateById because it has to lookup for the recipient Id)
InMobile::recipients()->updateByNumber(
    $listId,
    $prefix,
    $number,
     \Juanparati\InMobile\Models\Recipient::make()
        ->addField('firstname', 'John')
        ->addField('lastname', 'Random')
        ->addField('custom1', 'foobar')
);
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

#### Find recipient

```php
// Search by phone number
$recipient = InMobile::recipients()->findByNumber('listid', '45', '12345678');

// or search by recipient id
$recipient = InMobile::recipients()->findById('listid', 'recipientId');

```

#### Other methods
- deleteById
- deleteByNumber
- updateOrCreateByNumber
- moveToList


### Blacklist service

#### Methods
- all
- create
- findById
- findByNumber
- deleteById
- deleteByNumber


### Email service


#### Send message

```PHP
$message = \Juanparati\InMobile\Models\Message(
    '+45', 
    '1234567',
    'from me',
    'Hello world'
);

InMobile::sms()->send([$message]);
```

#### Send template message

```PHP
$templateMessage = \Juanparati\InMobile\Models\MessageTemplate::make('+45', '1234567');
$templateMessage->addPlaceholder('{NAME}', 'John Random');

InMobile::sms()->sendUsingTemplate('FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF', [$templateMessage]);
```

### Email template service

#### Methods
- all
- find


### Gdpr service
- create


### Sms service

#### Methods
- send
- sendUsingTemplate
- cancel
- status
