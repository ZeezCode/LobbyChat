<?php
    include 'db_info.php';
    include 'utilities.php';

    if (!isset($_POST['id'])) {
        header('Location: index.php');
    }

    $dbconnect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        die("Connection error: " . mysqli_connect_error());
    }

    $lobbyInfo = getLobbyInfo($_POST['id'], $dbconnect);
    if ($lobbyInfo == null) {
        header('Location: index.php?e=2');
    }

    if ($lobbyInfo['password'] != "") {
        $enteredPass = md5( md5($_POST['pass']) . md5($lobbyInfo['salt']) );
        if ($enteredPass != $lobbyInfo['password']) {
            header('Location: index.php?e=3');
        }
    }
?>

<html>
    <head>
        <title><?php echo $lobbyInfo['lid']; ?> :: LobbyChat</title>
        <script src="jquery-3.1.1.min.js"></script>
        <style>
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            #stage {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 75%;
                text-align: center;
                display: table;
                background-color: #BABABA;
            }

            #stagespan {
                margin-top: 33%
                display: table-cell;
                vertical-align: middle;
                font-size: 50px;
            }

            #lobbyid {
                position: absolute;
                font-size: 10px;
                left: 10px;
                top: 68%;
            }

            #greenroom {
                padding: 0;
                margin: 0;
                marign-top: 75%;
                width: 100%;
                height: 25%;
            }

            input[type="text"] {
                padding: 0;
                margin: 0;
                width: 100%;
                height: 100%;
                font-size: 75px;
            }
        </style>
    </head>
    <body>
        <div id="stage">
            <span id="lobbyid"><h1><?php echo "Lobby ID: " . $lobbyInfo['lid']; ?></h1></span>
            <span id="stagespan"><h1 id="stagemsg"></h1></span>
        </div>

        <div id="greenroom">
            <form action="javascript:;" onsubmit="sendMessage(this)">
                <input type="text" name="typedmsg" id="typedmsg" placeholder="ENTER message here..." maxlength="64" />
                <input type="hidden" name="lobby" value="<?php echo $lobbyInfo['lid']; ?>" />
                <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
            </form>
        </div>

        <input type="hidden" id="lobby" value="<?php echo $lobbyInfo['lid']; ?>" />
    </body>

    <script>
        function sendMessage(form) {
            console.log("Sending...");
            $.ajax({
                type: "GET",
                url: "send_message.php",
                data: {message:form.typedmsg.value, lobbyID:form.lobby.value, ip:form.ip.value},
                dataType: 'text',
                success: function(data) {
                }
            });
            form.typedmsg.value = "";
        }
        var lastMsg = 0;
        function getLastMessage(lastMessage, lobby, first) {
            $.ajax({
                type: "GET",
                url: "get_message.php",
                data: {lastMessage:lastMessage, lobbyID:lobby, first:first},
                dataType: 'json',
                success: function(data) {
                    if (data != null) {
                        console.log(data);
                        lastMsg = data.mid;
                        $("#stagemsg").text(data.message);
                    } else {
                        console.log("Data is null");
                    }
                }
            });
        }

        getLastMessage(0, document.getElementById("lobby").value, "true");
        window.setInterval(function() {
            $("#stagemsg").text("");
            getLastMessage(lastMsg, document.getElementById("lobby").value, "false");
        }, 5000);
    </script>
</html>
