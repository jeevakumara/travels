<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
function rate_limit($key, $windowSeconds = 60) {
  $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $bucket = "rl_{$key}_{$ip}";
  $now = time();
  if (!isset($_SESSION[$bucket])) {
    $_SESSION[$bucket] = $now;
    return true;
  }
  if (($now - $_SESSION[$bucket]) < $windowSeconds) {
    return false;
  }
  $_SESSION[$bucket] = $now;
  return true;
}
