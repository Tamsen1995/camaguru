
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