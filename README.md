# bubujka/pushover

Binding to pushover api.

## Installing

From cli:
```bash
$ composer require bubujka/pushover=dev-master
```

composer.json:
```json
"require": {
    "bubujka/pushover": "dev-master"
}
```

## Usage

Configure:
```php
pushover_token('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
pushover_user('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
```

User-wide configuration (~/.pushoverrc):
```json
{
  "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user":  "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

Use:
```php
pushover('My Message');
pushover('My another message', 'With title');
pushover('My third message', 'With title and priority', 1);
pushover(array(
  'title'=>'Read TL;DR section on api page',
  'message'=>'For more information...',
  'url'=>'https://pushover.net/api'
));

print_r(pushover_safe('How about return value?'));
#  Array
#  (
#    [status] => 1
#    [request] => 3eea15e35211008dee9fdd83342e856b
#  )
```
