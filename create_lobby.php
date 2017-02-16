<?php
    include 'db_info.php';
    include 'utilities.php';

    $dbconnect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        die("Connection error: " . mysqli_connect_error());
    }

    runGarbageCollector($dbconnect);

    if (isset($_POST['password'])) {
        $lobby = createLobby($_POST['password'], $_SERVER['REMOTE_ADDR'], $dbconnect);
    }
    else {
        header('Location: index.php');
    }
?>
<form id="redirectForm" action="lobby.php" method="post">
    <input type="hidden" name="id" value="<?php echo $lobby['lid']; ?>">
    <input type="hidden" name="pass" value="<?php echo $lobby['pass']; ?>">
</form>

<script>
    document.getElementById('redirectForm').submit();
</script>