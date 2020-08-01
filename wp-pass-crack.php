#!/usr/bin/env php
<?php

if ($argc === 1 || in_array($argv[1], ['-h', '-help', '--help', '-?'])) {
?>
  Usage:
  <?= $argv[0] ?> password_hash [ dictionary_filename ]
<?php
  die;
}

$password_hash = $argv[1];
$dictionary_filename = $argv[2] ?? __DIR__ . DIRECTORY_SEPARATOR . 'dict.txt';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class-phpass.php';

// https://github.com/WordPress/WordPress/blob/aad1fa48ea68b05329eec92e1cca8b178b1641f7/wp-login.php#L758
$hasher = new PasswordHash(8, true);

$file = new SplFileObject($dictionary_filename);
while (!$file->eof()) {
  $password = rtrim($file->fgets());
  fprintf(STDOUT, "\r\033[KCheck: %s", $password);
  if ($hasher->CheckPassword($password, $password_hash)) {
    fprintf(STDOUT, "\r\033[K\e[32mFound: \e[1m\e[39m%s\n", $password);
    exit(0);
  }
}
fprintf(STDERR, "\r\033[K\e[1;31mNot found :-(\n");
exit(1);
