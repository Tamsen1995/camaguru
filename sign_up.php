<?php
session_start();
// If user is logged in, header them away
if (isset($_SESSION["username"])) {
    header("location: message.php?msg=NO to that weenis");
    exit();
}
?>

<?php
// This block checks for a valid username
    if (isset($_POST["usernamecheck"])) {
        include_once("php_includes/db_conx.php");
        $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
        $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
        $uname_check = mysqli_num_rows($query);
        if (strlen($username) < 3 || strlen(username) > 16) {
            echo '<strong style="color:#F00;">3 - 16 chracters please</strong>';
            exit();
        }
        if (is_numeric($username[0])) {
            echo '<string style="color:#F00;">Usernames must begin with a letter</strong>';
            exit();
        }
        if ($uname_check < 1) {
	        echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
            exit();
        } else {
	        echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	        exit();
        }
    }
?>

<?php
    // Ajax calls this REGISTRATION code to execute
    if (isset($_POST["u"])) {
        // CONNECTING TO DB
        include_once("php_includes/db_conx.php");
        // GATHER THE POSTED DATA INTO LOCAL VARS
	    $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
        $e = mysqli_real_escape_string($db_conx, $_POST['u']);
        $p = $_POST['p'];
	    $g = preg_replace('#[^a-z]#', '', $_POST['g']);
        $c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
        // GET USER IP ADDRESS
        $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
        // DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
        $sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $u_check = mysqli_num_rows($query);
        // ------------------------------------------
        $sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    }

?>