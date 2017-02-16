<?php
    include 'db_info.php';
    include 'utilities.php';

    $dbconnect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        return $result;
    }

    $lobby = $_GET['lobbyID'];
    $lobbyCT = $_GET['lobbyCT']; //lobby creation time
    $msg = trim($_GET['message']);

    $lobbyInfo = getLobbyInfo($lobby, $dbconnect);

    if ($lobbyInfo != null && $lobbyInfo['creation_time'] == $lobbyCT) {
        if (getLobbyInfo($lobby, $dbconnect) != null) {
            $createMessageSQL = sprintf("INSERT INTO messages VALUES (%d, %d, '%s', '%s', %d);",
                mysqli_real_escape_string($dbconnect, 0),
                mysqli_real_escape_string($dbconnect, $lobby),
                mysqli_real_escape_string($dbconnect, $msg),
                mysqli_real_escape_string($dbconnect, $_SERVER['REMOTE_ADDR']),
                mysqli_real_escape_string($dbconnect, time()));
            mysqli_query($dbconnect, $createMessageSQL);
        }
    }