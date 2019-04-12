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

class LoadPages {

    public static function login() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>BinaryChat - Login</title>

            <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/screen.css" />
        </head>
        <body>
        <main class="centerAligned">
            <div>
                <form id="login" class="left">
                    <h3>Already have an account?</h3>
                    <h1>Log In Here</h1>
                    <input type="text" name="usrName" id="usrName" placeholder="Username" required="required" />
                    <input type="password" name="pwd" id="pwd" placeholder="Password" required="required" />
                    <div class="leftInput"><div class="checkbox"><input type="checkbox" name="keepLogin" id="keepLogin" /><span></span></div><label for="keepLogin">Keep me logged in</label></div>
                    <input type="submit" name="logBut" id="logBut" value="Login" />
                    <span class="result"></span>
                    <a href="#">Forgot password?</a>
                </form>
                <span id="or">or</span>
                <form id="register" class="right">
                    <h3>Don't have an account?</h3>
                    <h2>Register Now</h2>
                    <input type="text" name="desUsrName" id="desUsrName" placeholder="Desired Username" required="required" />
                    <input type="password" name="desPwd" id="desPwd" placeholder="Password" required="required" />
                    <input type="password" name="desPwdConfirm" id="desPwdConfirm" placeholder="Confirm Password" required="required" />
                    <input type="submit" name="regBut" id="regBut" value="Register" />
                    <span class="result"></span>
                </form>
            </div>
        </main>

        <script src="assets/js/submitForm.js"></script>
        </body>
        </html>
        <?php
    }

    public static function dashboard($username) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>BinaryChat - Dashboard</title>

            <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/screen.css" />
        </head>
        <body>

        <audio controls>
            <source src="assets/media/tone.wav" type="audio/wav">
            <source src="assets/media/tone/mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <header>
            <h1>Welcome to BinaryChat!</h1>
            <section><h2 id="usrHeader"><?php echo $username ?></h2>
                <nav>
                    <div id="accountIcon">
                        <span class="spike"></span>
                        <ul>
                            <li>
                                <form id="settings">
                                    <input type="submit" name="setBut" id="setBut" value="Settings"/>
                                    <span class="result"></span>
                                </form>
                            </li>
                            <li>
                                <form id="logout">
                                    <input type="submit" name="logoutBut" id="logoutBut" value="Logout"/>
                                    <span class="result"></span>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </section>
        </header>

        <div class="flex-wrapper">
            <aside class="left">
                <form id="createChat">
                    <div class="one-line">
                        <input type="text" name="chatName" id="chatName" placeholder="Name" required="required" autocomplete="off" />
                        <input type="submit" name="createChatBut" id="createChatBut" value="Create chat" />
                    </div>
                    <span class="result"></span>
                </form>
                <!-- Here will come an ul > li kinda thing -->
                <ul id="chatList">
                    <li>No chat loaded</li>
                </ul>
            </aside>
            <main class="middle">
                <ul id="message-container">
                    <!--
                    <li class="send">
                        <span>Sent 2018-03-14 18:37:27</span>
                        <h3>BinaryFour</h3>
                        <p>
                            Hey!
                        </p>
                    </li>
                    <li class="received">
                        <span>Sent 2018-03-14 18:37:25</span>
                        <h3>admin</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        </p>
                    </li>
                    -->
                </ul>
                <form id="sendMessage">
                    <div class="one-line">
                        <input type="text" name="message" id="message" placeholder="Type a message" autocomplete="off" />
                        <input type="hidden" id="chatId2" name="chatId" />
                        <input type="submit" name="sendMessageBut" id="sendMessageBut" value="Send" />
                    </div>
                    <span class="result">Balalla</span>
                </form>
            </main>
            <aside class="right">
                <form id="addMember">
                    <div class="one-line">
                        <input class="searchBar" type="text" id="searchUser" name="searchUser" placeholder="Search user" required="required" autocomplete="off" />
                        <input type="hidden" id="chatId" name="chatId" />
                        <input type="submit" name="addMemberBut" id="addMemberBut" value="Add member"/>
                    </div>
                    <span class="result"></span>
                    <ul class="searchList" id="searchUser-result"></ul>
                </form>
                <!-- Here will come a list of all members in the active chat -->
                <ul id="memberList">
                    <li>No chat selected</li>
                </ul>
            </aside>
        </div>

        <script src="assets/js/activeSearchBar.js"></script>
        <script src="assets/js/submitForm.js"></script>
        <script src="assets/js/responsive.js"></script>
        <script src="assets/js/updateChats.js"></script>
        </body>
        </html>
        <?php
    }

}