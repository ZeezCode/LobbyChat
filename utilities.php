<?php
    define("MAX_AGE", 3600);

    function getLobbyInfo($lid, $dbconnect) {
        $lobbyInfo = array();
        $getLobbySQL = sprintf("SELECT * FROM lobbies WHERE lid = %d;",
            mysqli_real_escape_string($dbconnect, $lid));
        $getLobbyQuery = mysqli_query($dbconnect, $getLobbySQL);
        if (mysqli_num_rows($getLobbyQuery) != 0) {
            $lobbyInfo = mysqli_fetch_assoc($getLobbyQuery);
        }
        return $lobbyInfo;
    }

    function createLobby($pass, $ip, $dbconnect) {
        $salt = generateRandomString();
        $createLobbySQL = sprintf("INSERT INTO lobbies VALUES (%d, '%s', '%s', %d, %d, '%s');",
            mysqli_real_escape_string($dbconnect, $lid = rand(111111, 999999)),
            mysqli_real_escape_string($dbconnect, md5( md5($pass) . md5($salt) )),
            mysqli_real_escape_string($dbconnect, $salt),
            mysqli_real_escape_string($dbconnect, time()),
            mysqli_real_escape_string($dbconnect, time()),
            mysqli_real_escape_string($dbconnect, $ip));
        mysqli_query($dbconnect, $createLobbySQL);
        $lobby = array();
        $lobby['lid'] = $lid;
        $lobby['pass'] = $pass;
        return $lobby;
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function runGarbageCollector($dbconnect) {
        $getOldLobbiesSQL = sprintf("SELECT * FROM lobbies WHERE UNIX_TIMESTAMP() - last_active > %d;",
            mysqli_real_escape_string($dbconnect, MAX_AGE));
        $getOldLobbiesQuery = mysqli_query($dbconnect, $getOldLobbiesSQL);
        if (mysqli_num_rows($getOldLobbiesQuery) != 0) {
            while ($lobbyInfo = mysqli_fetch_assoc($getOldLobbiesQuery)) {
                $lid = $lobbyInfo['lid'];
                $removeLobbySQL = sprintf("DELETE FROM lobbies WHERE lid = %d;",
                    mysqli_real_escape_string($dbconnect, $lid));
                mysqli_query($dbconnect, $removeLobbySQL);
                $removeLobbyMessagesSQL = sprintf("DELETE FROM messages WHERE lid = %d;",
                    mysqli_real_escape_string($dbconnect, $lid));
                mysqli_query($dbconnect, $removeLobbyMessagesSQL);
            }
        }
    }