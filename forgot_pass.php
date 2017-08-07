<?php include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($user_ok == true) {
    header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?>
<?php include_once("php_includes/generate_temp_pass.php");?>
<?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
// This block of code changes the password in the db once the link in the email has been clicked
if (isset($_GET['u']) && isset($_GET['p'])) {
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
    if (strlen($temppasshash) < 10) {
        exit ();
    }
    $sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
	if ($numrows == 0) {
		header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
    	exit();
	} else {
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    #forgotpassform {
        margin-top:24px;
    }
    #forgotpassform > div {
        margin-top: 12px;
    }
    #forgotpassform > input {
        width: 250px;
        padding: 3px;
        background: #F3F9DD;
    }
    #forgotpassbtn {
        font-size:15px;
        padding: 10px;
    }
    </style>
    <script src="javascript/main.js"></script>
    <script src="javascript/ajax.js"></script>
    <script>
        function forgotpass(){
            var e = _("email").value;
            if (e == ""){
                _("status").innerHTML = "Type in your email address";
            } else {
                _("forgotpassbtn").style.display = "none";
                _("status").innerHTML = 'please wait ...';
                var ajax = ajaxObj("POST", "forgot_pass.php");
                ajax.onreadystatechange = function() {
                    if(ajaxReturn(ajax) == true) {
                        var response = ajax.responseText;
                        if(response == "\nsuccess"){
                            _("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
                        } else if (response == "no_exist"){
                            _("status").innerHTML = "Sorry that email address is not in our system";
                        } else if(response == "email_send_failed"){
                            _("status").innerHTML = "Mail function failed to execute";
                        } else {
                            _("status").innerHTML = "An unknown error occurred";
                        }
                    }
                }
                ajax.send("e="+e);
            }
        }
    </script>
    </head>
    <body>
    <?php include_once("php_includes/table_top.php");?>
    <div id = "pageMiddle"> 
        <h3>Generate a temporary log in password</h3>
        <form id = "forgotpassform" onsubmit="return false;">
            <div>Step 1: Enter Your Email Address</div>
            <input id="email" type="text" onfocus="_('status').innerHTML='';" maxlength="88">
            <br /><br />
            <button id="forgotpassbtn" onclick="forgotpass()">Generate Temporary Log In Password</button>
            <p id="status"></p>
        </form>
    </div>
    <?php // TODO make a page bottom ?>
    </body>
</html>