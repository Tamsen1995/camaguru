<?php
// AJAX CALLS THIS CODE TO EXECUTE
// This block of code generates a new temp password for the user
// And then also generates and sends an email to the user
if (isset($_POST["e"])) {
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $sql = "SELECT id, username FROM users WHERE email='$e' AND activated='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if ($numrows > 0) {
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $id = $row["id"];
            $u = $row["username"];
        }
        $emailcut = substr($e, 0, 4);
        $randNum = rand(10000, 999999);
        $tempPass = "$emailcut$randNum";
        $hashTempPass = $tempPass; // TODO hash this later with md5
        $sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $to = "$e";
        $from = "auto_responder@camaguru.com";
        $headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
        $subject = "Camaguru temporary Password";
		$msg = '<h2>Hello '.$u.'</h2><p>This is an automated message from the Tamaguru. If you did not recently initiate the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="http://www.yoursite.com/forgot_pass.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown below to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
        if (mail($to, $subject, $msg, $headers)) {
            echo ("success");
            exit();
        } else {
            echo "email_send_failed";
            exit ();
        }
    } else {
        echo "no_exist";
    }
    exit();
}?>