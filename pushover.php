<?php
require 'vendor/autoload.php';

def_accessor('pushover_token');
def_accessor('pushover_user');
def_accessor('pushover_curl_timeout', 5);
def('pushover', function($msg_or_array, $title=null, $priority=null){
  $opts = array();
  if(is_array($msg_or_array)){
    $opts = $msg_or_array;
  }else{
    $opts['message'] = $msg_or_array;
    if(!is_null($title))
      $opts['title'] = $title;
    if(!is_null($priority))
      $opts['priority'] = $priority;
  }
  $opts['token'] = pushover_token();
  $opts['user']  = pushover_user();
  curl_setopt_array($ch = curl_init(), array(
    CURLOPT_URL => "https://api.pushover.net/1/messages.json",
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_CONNECTTIMEOUT => pushover_curl_timeout(),
    CURLOPT_POSTFIELDS => $opts));
  $data = curl_exec($ch);
  curl_close($ch);
  return json_decode($data, true);
});
