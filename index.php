<?php
    include 'db_info.php';
    include 'utilities.php';

    $dbconnect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        die("Connection error: " . mysqli_connect_error());
    }

    runGarbageCollector($dbconnect);
?>
<html>
    <head>
        <title>Lobby Chat - v.0.1 Alpha</title>
        <script src="jquery-3.1.1.min.js"></script>
        <style>
            #joinlobby, #createlobby {
                border: 1px solid #000;
                width: 20%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 50px;
                font-size: 32px;
                font-family: Arial, Helvetica, sans-serif;
            }
            #joinlobbyform, #createlobbyform{
                text-align: center;
            }
            input[type="text"], input[type="password"], input[type="submit"] {
                width: 90%;
                height: 50px;
                font-size: 32px;
            }
            input[type="submit"] {
                color: black;
                border: 1px solid gray;
                border-radius: 25px;
                -moz-border-radius: 25px;
                -webkit-border-radius: 25px;
                background-color: white;
                -webkit-appearance: none;
            }
        </style>
    </head>
    <body>
        <div id="joinlobby">
            <form id="joinlobbyform" action="lobby.php" method="post">
                <label for="joinlobbyid">Lobby ID: </label><input type="text" placeholder="Ex: 12345" name="id" id="joinlobbyid" /><br />
                <label for="joinlobbypass">Password: </label><input type="password" placeholder="Password" name="pass" id="joinlobbypass" /><br />
                <br />
                <input type="submit" value="Join" />
            </form>
        </div>

        <div id="createlobby">
            <form id="createlobbyform" action="create_lobby.php" method="post">
                <label for="private">Private: </label><input type="checkbox" name="private" id="private" /><br />
                <input type="password" name="password" id="password" placeholder="Password" /><br />
                <br />
                <input type="submit" value="Create" />
            </form>
        </div>
        <script>
            $(function() {
                $('input[name="password"]').hide();

                $('input[name="private"]').on('click', function() {
                    if ($(this).prop('checked')) {
                        $('input[name="password"]').fadeIn();
                    } else {
                        $('input[name="password"]').fadeOut();
                    }
                });
            });
        </script>
    </body>