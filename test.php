<?php
require_once 'pushover.php';

pushover_token('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
pushover_user('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

pushover('My Message');
pushover('My another message', 'With title');
pushover('My third message', 'With title and priority', 1);
pushover(array(
    'title'=>'Read TL;DR section on api page',
      'message'=>'For more information...',
        'url'=>'https://pushover.net/api'
      ));
print_r(pushover('How about return value?'));

