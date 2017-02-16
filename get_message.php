<?php
    include 'db_info.php';
    include 'utilities.php';

    $result = array();

    $lastMsg = $_GET['lastMessage'];

    $dbconnect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        echo json_encode($result);
        die(0);
    }

    if (getLobbyInfo($_GET['lobbyID'], $dbconnect) == null) {
        echo json_encode($result);
        die(0);
    }

    if ($_GET['first'] == "false") {
        $getNextMessageSQL = sprintf("SELECT mid, message FROM messages WHERE lid = %d AND mid > %d ORDER BY mid ASC LIMIT 1;",
            mysqli_real_escape_string($dbconnect, $_GET['lobbyID']),
            mysqli_real_escape_string($dbconnect, $_GET['lastMessage']));
        $result['type'] = 1;
    }
    else {
        $getNextMessageSQL = sprintf("SELECT mid, message FROM messages WHERE lid = %d ORDER BY timestamp DESC LIMIT 1;",
            mysqli_real_escape_string($dbconnect, $_GET['lobbyID']));
        $result['type'] = 2;
    }
    $getNextMessageQuery = mysqli_query($dbconnect, $getNextMessageSQL);
    if (mysqli_num_rows($getNextMessageQuery) == 0) return $result;
    $nextMessageInfo = mysqli_fetch_assoc($getNextMessageQuery);

    $result['mid'] = $nextMessageInfo['mid'];
    $result['message'] = $nextMessageInfo['message'];

    echo json_encode($result);