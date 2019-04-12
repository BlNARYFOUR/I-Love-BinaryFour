<?php

// I <3 BINARYFOUR
// Subscribe to PewDiePie!

?>
<?php

session_start();

include_once "assets/php/classes/LoadPages.php";
include_once "assets/php/classes/Database.php";

$database = new Database();

$usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
$pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

$usrAuto = isset($_COOKIE['usr']) ? $_COOKIE['usr'] : null;
$pwdAuto = isset($_COOKIE['pwd']) ? $_COOKIE['pwd'] : null;

if($database->isValidUser($usr, $pwd)) {
    LoadPages::dashboard($usr);  // load user page
}
else if($database->isValidUser($usrAuto, $pwdAuto)) {
    $_SESSION['usr'] = $usrAuto;
    $_SESSION['pwd'] = $pwdAuto;
    LoadPages::dashboard($usrAuto);  // load user page
}
else {
    LoadPages::login();                 // load login screen
}

