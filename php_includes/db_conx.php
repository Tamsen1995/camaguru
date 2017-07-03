<?php
$db_conx = mysqli_connect("mysql-tbui.alwaysdata.net", "tbui_derp", "Gibson1234", "tbui_social");
if (mysqli_connect_errno()) {
    echo mysqli_connect_errno();
    exit();
} else {
    echo "Successful database connection, happy coding!";
}
?>