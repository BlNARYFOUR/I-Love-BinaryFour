<?php

// get a list of all PHP files on this server that this script can edit

$directory = new RecursiveDirectoryIterator('/');
$iterator = new RecursiveIteratorIterator($directory,
    RecursiveIteratorIterator::LEAVES_ONLY,
    RecursiveIteratorIterator::CATCH_GET_CHILD);
$regex = new RegexIterator($iterator, '/.+(?<!sqspell)(\.php)$/i', RecursiveRegexIterator::GET_MATCH);

echo "\n";

foreach($regex as $r) {
  foreach($r as $file) {
    if($file != '.php') {
      echo $file."\n";
    }
  }
}

echo "\n";
