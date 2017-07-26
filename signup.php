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
        function emptyElement(x) {
            _(x).innerHTML = "";
        }
       function checkusername() {
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

        


    </script>


</head>

<body>
    <div id ="pageTop">
        <?php include_once("php_includes/table_top.php"); ?>
    </div>


    <div id="pageMiddle">
        <form name="signupform" id="signupform" onsubmit="return false;">
            <div>Username: </div>
            <input id="username" type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
            <span id="unamestatus"></span>
            <div>Email Address:</div>
            <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
            <div>Create Password:</div>
            <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
            <div>Confirm Password:</div>
            <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="16">
            <div>Gender:</div>
            <select id="gender" onfocus="emptyElement('status')">
            <option value=""></option>
            <option value="m">Male</option>
            <option value="f">Female</option>
            </select>
            <div>Country:</div>
            <select id="country" onfocus="emptyElement('status')">
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