<?php

// I <3 BINARYFOUR
// Subscribe to PewDiePie!

 ?>
<?php

// I <3 BINARYFOUR
// Subscribe to PewDiePie!

 ?>
<?php

// I <3 BINARYFOUR
// Subscribe to PewDiePie!

 ?>
<?php

// I <3 BINARYFOUR
// Subscribe to PewDiePie!

 ?>

<?php

session_start();

include_once "classes/Database.php";

$postRequest = isset($_POST['request']) ? $_POST['request'] : null;
$getRequest = isset($_GET['request']) ? $_GET['request'] : null;
$reply = "Not even been in the switchCase?";
$salt = "SaltyIsThisSalt";

switch($postRequest) {
    case "login":
        $reply = login($salt);
        break;
    case "register":
        $reply = register($salt);
        break;
    case "logout":
        $reply = logout();
        break;
    case "createChat":
        $reply = createChat();
        break;
    case "addMember":
        $reply = addMember();
        break;
    case "sendMessage":
        $reply = sendMessage();
        break;
    default:
        $reply = "Unrecognized request";
        break;
}

switch($getRequest) {
    case "search":
        $reply = search();
        break;
    case "chatlist":
        $reply = getChats();
        break;
    case "members":
        $reply = getChatMembers();
        break;
    case "chat":
        $reply = getMessages();
        break;
}

echo json_encode($reply);

function sendMessage() {
    $database = new Database();

    $message = isset($_POST['message']) ? $_POST['message'] : null;
    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : null;

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        if ($message == "") {
            $result = "Enter a members name";
        } else {
            $usrId = $database->getUserId($usr);

            if($database->isMemberOfChat($chatId, $usrId)) {
                $succeeded = $database->sendMessage($chatId, $usrId, $message);

                if ($succeeded) {
                    $result = "Message sent ".$usr;
                } else {
                    $result = "Sending message failed";
                }
            }
            else {
                $result = "You cannot add send messages to a chat you are not part of";
            }
        }
    }
    else {
        $result = "You need to login first!";
    }

    return $result;
}

function getMessages() {
    $result = "Select an existing chat";
    $database = new Database();

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        $id = $database->getUserId($usr);
        $chatId = isset($_GET['id']) ? $_GET['id'] : null;
        $beginIndex = isset($_GET['beginIndex']) ? $_GET['beginIndex'] : null;
        $amount = isset($_GET['amount']) ? $_GET['amount'] : null;

        if($chatId != null) {
            $result = $database->getMessages($chatId, $id, $beginIndex, $amount);
        }
    }
    else {
        $result = ["You need to login first!"];
    }

    return $result;
}

function addMember() {
    $database = new Database();

    $searchUser = isset($_POST['searchUser']) ? $_POST['searchUser'] : null;
    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : null;

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        if ($searchUser == "") {
            $result = "Enter a members name";
        } else {
            $usrId = $database->getUserId($usr);

            if($database->isMemberOfChat($chatId, $usrId)) {
                $id = $database->getUserId($searchUser);
                $succeeded = $database->addMember($chatId, $id);

                if ($succeeded) {
                    $result = "Member added";
                } else {
                    $result = "Adding member failed";
                }
            }
            else {
                $result = "You cannot add members to a chat you are not part of";
            }
        }
    }
    else {
        $result = "You need to login first!";
    }

    return $result;
}

function getChats() {
    $database = new Database();

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        $id = $database->getUserId($usr);
        $result = $database->getChatsForUser($id);
    }
    else {
        $result = ["You need to login first!"];
    }

    return $result;
}

function getChatMembers() {
    $database = new Database();

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        $id = isset($_GET['chat']) ? $_GET['chat'] : null;
        $result = $database->getChatMembers($id);
    }
    else {
        $result = ["You need to login first!"];
    }

    return $result;
}

function search() {
    $database = new Database();
    $reply = "Nothing to search";
    $value = isset($_GET['value']) ? $_GET['value'] : null;

    if(isset($value)) {
        $reply = $database->getUsernamesLike($value);
    }

    return $reply;
}

function logout() {
    session_unset();

    setcookie("usr", "", time() - 3600, "/");
    setcookie("pwd", "", time() - 3600, "/");

    return "Logout succeeded";
}

function login($salt) {
    $result = "Login failed";
    $database = new Database();

    $usr = isset($_POST['usrName']) ? $_POST['usrName'] : null;
    $pwd = isset($_POST['pwd']) ? md5($_POST['pwd'] . $salt) : null;
    $rememberMe = isset($_POST['keepLogin']) ? $_POST['keepLogin'] : false;

    if($usr == "") {
        $result = "Enter a username";
    }
    else if($pwd == "") {
        $result = "Enter a password";
    }
    else {
        $succeeded = $database->isValidUser($usr, $pwd);
        if($succeeded) {
            $result = "Login succeeded";

            if($rememberMe) {
                setcookie("usr", $usr, time() + 31536000, "/");
                setcookie("pwd", $pwd, time() + 31536000, "/");
            }

            $_SESSION['usr'] = $usr;
            $_SESSION['pwd'] = $pwd;
        }
        else {
            $result = "Invalid username or password";
        }
    }

    return $result;
}

function register($salt) {
    $result = "Registration failed";
    $database = new Database();

    $usr = isset($_POST['desUsrName']) ? $_POST['desUsrName'] : "";
    $pwd = isset($_POST['desPwd']) ? md5($_POST['desPwd'] . $salt) : "";
    $pwdConfirm = isset($_POST['desPwdConfirm']) ? md5($_POST['desPwdConfirm'] . $salt) : "";

    if($usr == "") {
        $result = "Enter a username";
    }
    else if($database->findUser($usr)) {
        $result = "Username already exists";
    }
    else if($pwd == "") {
        $result = "Enter a password";
    }
    else if($pwd != $pwdConfirm) {
        $result = "Passwords not matching";
    }
    else {
        $succeeded = $database->addUser($usr, $pwd, "");
        if($succeeded) {
            $result = "Registration succeeded";
            $_SESSION['usr'] = $usr;
            $_SESSION['pwd'] = $pwd;
        }
        else {
            $result = "Unknown error";
        }
    }

    return $result;
}

function createChat() {
    $database = new Database();

    $chatName = isset($_POST['chatName']) ? $_POST['chatName'] : null;

    $usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : null;
    $pwd = isset($_SESSION['pwd']) ? $_SESSION['pwd'] : null;

    if($database->isValidUser($usr, $pwd)) {
        if ($chatName == "") {
            $result = "Enter a chatname";
        } else {
            $id = $database->getUserId($usr);
            $succeeded = $database->addChat($chatName, $id);

            if($succeeded) {
                $result = "Chat created";
            }
            else {
                $result = "Chat creation failed";
            }
        }
    }
    else {
        $result = "You need to login first!";
    }

    return $result;
}