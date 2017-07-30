<?php 

if (isset($_POST["u"])) {
    // connecting to database
    include_once("php_includes/db_conx.php");
    // gather the posted data into local variables
    $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']); // username sanitized
    $e = mysqli_real_escape_string($db_conx, $_POST['e']); // email sanitized
    $p = $_POST['p']; // password
    $g = preg_replace('#[^a-z0-9]#', '', $_POST['g']); // gender sanitized
    $c = preg_replace('#[^a-z0-9]#', '', $_POST['c']); // country sanitized
    // getting the user ip address
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR')); // ip address sanitized
    // duplicate data checks for username and email
    $sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $u_check = mysqli_num_rows($query);
    //---------------------------------------
    $sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $e_check = mysqli_num_rows($query);
    // FORM DATA HANDLING
    //if any of the fields are empty then echo an error
    // if username is taken
    // if email is taken
    if ($u == "" || $e == "" || $p == "" || $g == "" || $c == "") {
        echo "Something is missing ! either username, or email, or, password, or country, or gender";
        exit();
    } else if ($u_check > 0) {
        echo "Sorry brah, that username is already taken...brah";
        exit();
    } else if ($e_check > 0) {
        echo "Email is already taken. Do you have an account already ?";
        exit();
    } else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "The username has to be between 3 - 16 characters long... sorry brah";
        exit();
    } else if (is_numeric($u[0])) {
        echo "YOUR USERNAME HAS TO START WITH A LETTER BROOOO ... brah";
        exit();
    }

    // FORM DATA HANDLING END
	// Add user info into the database table for the main site table
	$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)       
	        VALUES('$u','$e','$p','$g','$c','$ip',now(),now(),now())";
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
    $from = "your@mom.de";
    $subject = 'camaguru Activation';
	$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>yoursitename Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="/camaguru/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';    $headers = "From: $from\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
    mail($to, $subject, $message, $headers);
    echo "signup_success";
    exit();
}

/*
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){

	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$cryptpass = crypt($p);
		include_once ("php_includes/randStrGen.php");
		$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
}
*/

?>
