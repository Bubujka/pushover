<?php
require 'vendor/autoload.php';

def_accessor('pushover_token', null);
def_accessor('pushover_user', null);

def('bu\pushover\check_global_config', function(){
  $pth = getenv('HOME').'/.pushoverrc';
  if(file_exists($pth)){
    $cfg = json_decode(file_get_contents($pth), true);
    pushover_token($cfg['token']);
    pushover_user($cfg['user']);
  }
});
bu\pushover\check_global_config();

def_accessor('pushover_curl_timeout', 5);
def('bu\pushover\parse_args', function($a){
  $opts = array();
  if(is_array($a[0])){
    $opts = $a[0];
  }else{
    $opts['message'] = $a[0];
    if(isset($a[1]))
      $opts['title'] = $a[1];
    if(isset($a[2]))
      $opts['priority'] = $a[2];
  }
  $opts['token'] = pushover_token();
  $opts['user']  = pushover_user();
  return $opts;
});

def('pushover', function(/* $args... */){
  if(is_null(pushover_token()) or is_null(pushover_user()))
    return;
  $opts = bu\pushover\parse_args(func_get_args());
  bu\pushover\post_async("https://api.pushover.net/1/messages.json", $opts);
});

def('pushover_safe', function(/* $args... */){
  if(is_null(pushover_token()) or is_null(pushover_user()))
    return;
  $opts = bu\pushover\parse_args(func_get_args());
  $data = bu\pushover\post_sync("https://api.pushover.net/1/messages.json", $opts);
  return json_decode($data, true);
});

def('bu\pushover\post_sync', function($url, $params){
  curl_setopt_array($ch = curl_init(), array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_CONNECTTIMEOUT => pushover_curl_timeout(),
    CURLOPT_POSTFIELDS => $params));
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
});
def('bu\pushover\post_async', function($url, $params){
  //http://stackoverflow.com/questions/962915/how-do-i-make-an-asynchronous-get-request-in-php
  foreach ($params as $key => &$val) {
    if (is_array($val)) $val = implode(',', $val);
      $post_params[] = $key.'='.urlencode($val);
  }
  $post_string = implode('&', $post_params);

  $parts=parse_url($url);

  $fp = fsockopen($parts['host'],
      isset($parts['port'])?$parts['port']:80,
      $errno, $errstr, 30);

  $out = "POST ".$parts['path']." HTTP/1.1\r\n";
  $out.= "Host: ".$parts['host']."\r\n";
  $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
  $out.= "Content-Length: ".strlen($post_string)."\r\n";
  $out.= "Connection: Close\r\n\r\n";
  if (isset($post_string)) $out.= $post_string;

  fwrite($fp, $out);
  fclose($fp);
});
