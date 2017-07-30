
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

    if (user == "" || pass1 == "" || pass2 == "" || country == "" || sex == "" || email == "") {
        status.innerHTML = "Fill out the form data";
    } else if (pass1 != pass2) {
        // check if the two passwords are equal to one another or not
        status.innerHTML = "The passwords you've entered are not identical to one another";
    } else if (_("terms").style.display == "none") {
        status.innerHTML = "Please view the terms and conditions";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = "Please wait ...";
        var ajax = ajaxObj("POST", "signup_form_test.php");
        ajax.onreadystatechange = function() {
                if (ajaxReturn(ajax) == true) {

                    console.log(ajax.responseText); // TESTING this should display signup_success

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

</script>

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
?>

<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <style type="text/css">
    #signupform{
        margin-top:24px;	
    }
    #signupform > div {
        margin-top: 12px;	
    }
    #signupform > input,select {
        width: 200px;
        padding: 3px;
        background: #F3F9DD;
    }
    #signupbtn {
        font-size:18px;
        padding: 12px;
    }
    #terms {
        border:#CCC 1px solid;
        background: #F5F5F5;
        padding: 12px;
    }
    </style>



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
            <option value="m">Male</option>
            <option value="f">Female</option>
            </select>
            <div>Country:</div>
            <select id="country" onfocus="delElem('status')">
            <option value="America">USA</option>
            <option value="France">Paris</option>
            </select>
            <div>
            <a href="#" onclick="return false" onmousedown="openTerms()">
                Fuck the Terms Of Use
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