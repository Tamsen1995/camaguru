<?php
    session_start();
    // if the user is already logged in they cannot sign up again
    if (isset($_SESSION["username"])) {
        header("location: message.php?msg=NO to that DUUUUDE");
        exit();
    }
?><?php 
    // Ajax calls this NAME CHECK code to execute
    if (isset($_POST["usernamecheck"])) {
        include_once("php_includes/db_conx.php");
        $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
        $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $uname_check = mysqli_num_rows($query);// will hold a 1 or a 0
        if (strlen($username) < 3 || strlen($username) > 16) {
	        echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
            exit();
        }
        if (is_numeric($username[0])) {
            echo '<strong style="color:#F00;">Username has to start with a letter !</strong>';
            exit();
        }
        if ($uname_check < 1) {
            echo '<strong style="color:#F00;">'. $username .' is OK</strong>';
            exit();
        } else {
    	    echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
            exit();
        }

    }
?><?php 

/*

// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" || $g == "" || $c == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){ 
        echo "That email address is already in use in the system";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$cryptpass = crypt($p);
		include_once ("php_includes/randStrGen.php");
		$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)       
		        VALUES('$u','$e','$p_hash','$g','$c','$ip',now(),now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		$uid = mysqli_insert_id($db_conx);
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$u")) {
			mkdir("user/$u", 0755);
		}
		// Email the user their activation link
		$to = "$e";							 
		$from = "auto_responder@yoursitename.com";
		$subject = 'yoursitename Account Activation';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>yoursitename Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.yoursitename.com/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}

*/

?>



<!DOCTYPE HTML>
<html>

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>


<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <script src="javascript/main.js"></script>
    <script src="javascript/ajax.js"></script>
    <script>

        function restrict(elem) {
            var tf = _(elem);
            var rx = new RegExp;

            if (elem == "email") {
                rx = /[' "]/gi;
            } else if (elem == "username") {
                rx = /[^a-z0-9]/gi;
            }
            tf.value = tf.value.replace(rx, "");
        }
        function delElem(x) {
            _(x).innerHTML = "";
        }
       function validateusername() {
            var u = _("username").value;
	        if (u != "") {
		        _("unamestatus").innerHTML = 'checking ...';
		        var ajax = ajaxObj("POST", "signup.php");
                ajax.onreadystatechange = function() {
	                if(ajaxReturn(ajax) == true) {
	                    _("unamestatus").innerHTML = ajax.responseText;
	                }
                }
                ajax.send("usernamecheck="+u);
	        }
        }

        function signup() {
            var user = _("username").value;
            var email = _("email").value;
            var pass1 = _("pass1").value;
            var pass2 = _("pass2").value;
            var country = ("country").value;
            var sex = _("gender").value;

            // check username, password (both), email, country, and gender
            // can't be empty
            if (user == "" || pass1 == "" || pass2 == "" || country == "" || sex == "" || email = "") {
                status.innerHTML = "Fill out the form data";
            } else if (p1 != p2) {
                // check if the two passwords are equal to one another or not
                status.innerHTML = "The passwords you've entered are not identical to one another";
            } else if (_("terms").style.display == "none") {
                status.innerHTML = "Please view the terms and conditions";
            } else {
                _("signupbtn").style.display = "none";
                status.innerHTML = "Please wait ...";
                var ajax = ajaxObj("POST", signup.php);
                ajax.onreadystatechange = function() {
                        if (ajaxReturn(ajax) == true) {
                            if (ajax.responseText != "signup_success") {
                                status.innerHTML = ajax.responseText;
                                _("signupbtn").style.display = "block";
                            } else {
                                window.scrollTo(0, 0);
                                _("signupform").innerHTML = "OK "+user+ "check your email inbox and junk mail box at <u>"+email+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
                            }
                        }
                }
                ajax.send("u="+user+"&e="+email+"&p="+pass1+"&c="+country+"&g="+sex);
            }
        }
        function openTerms() {
            _("terms").style.display = "block";
            delElem("status");
        }
        function addEvents() {
            _("elemID").addEventListener("click", func, false);
        }
        window.onload = addEvents;
    </script>


</head>

<body>
    <div id ="pageTop">
        <?php include_once("php_includes/table_top.php"); ?>
    </div>


    <div id="pageMiddle">
        <form name="signupform" id="signupform" onsubmit="return false;">
            <div>Username: </div>
            <input id="username" type="text" onblur="validateusername()" onkeyup="restrict('username')" maxlength="16">
            <span id="unamestatus"></span>
            <div>Email Address:</div>
            <input id="email" type="text" onfocus="delElem('status')" onkeyup="restrict('email')" maxlength="88">
            <div>Create Password:</div>
            <input id="pass1" type="password" onfocus="delElem('status')" maxlength="16">
            <div>Confirm Password:</div>
            <input id="pass2" type="password" onfocus="delElem('status')" maxlength="16">
            <div>Gender:</div>
            <select id="gender" onfocus="delElem('status')">
            <option value=""></option>
            <option value="m">Male</option>
            <option value="f">Female</option>
            </select>
            <div>Country:</div>
            <select id="country" onfocus="delElem('status')">
            <?php include_once("template_country_list.php"); ?>
            </select>
            <div>
            <a href="#" onclick="return false" onmousedown="openTerms()">
                View the Terms Of Use
            </a>
            </div>
            <div id="terms" style="display:none;">
            <h3>Web Intersect Terms Of Use</h3>
            <p>1. Play nice here.</p>
            <p>2. Take a bath before you visit.</p>
            <p>3. Brush your teeth before bed.</p>
            </div>
            <br /><br />
            <button id="signupbtn" onclick="signup()">Create Account</button>
            <span id="status"></span>
        </form>
    </div>

</body>
</html>