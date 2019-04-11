<?php

// get a list of all PHP files on this server that this script can edit
$filenames = glob('*.php');

foreach( $filenames as $filename ) {

  // output the filename
  echo $filename;

}
