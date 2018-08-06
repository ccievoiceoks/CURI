<?php
$dbhost = "dbhost.local";
$dblogi = "dbuser";
$dbpass = "dbpassword";
$dbbase = "cr";

$db = mysql_connect($dbhost, $dblogi, $dbpass) /*or die("Sorry, Databse down !")*/;
mysql_select_db($dbbase, $db) /*or die("Sorry, Database not available !")*/;
?>
