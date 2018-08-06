<?php
session_start(); //Session Start

include("./inc.auth.php");

$auth = new AuthClass();

if (isset($_POST["login"]) && isset($_POST["password"])) { //Username and password are entered correctly
    if (!$auth->auth($_POST["login"], $_POST["password"])) {
        echo "<h2 style=\"color:red;\">Wrong username or password.</h2>";
    }
}

if (isset($_GET["is_exit"])) {
    if ($_GET["is_exit"] == 1) {
        $auth->out(); //logout
        header("Location: ?is_exit=0"); //redirect after exit
    }
}

if (!$auth->isAuth()) //show login form
{
 echo '<form method="post" action="">';
 echo 'Login: <input type="text" name="login" value="';
 echo (isset($_POST["login"])) ? $_POST["login"] : '';
 echo '" /><br/>Password: <input type="password" name="password" value="" /><br/><input type="submit" value="Login" /></form>';
}
else // show admin page
{
 require("./inc.conn.php");
 require("./inc.functions.php");

 if(isset($_POST['type']))
 {
  $type = $_POST['type'];

  if($type == "changecaller")
  {
   if(isset($_POST['id']) and $_POST['id'] != "")
   {
    $caller_id = $_POST['id'];

    if(isset($_POST['remove']) and $holiday_id != "new")
    {
     $sql = "SELECT * from caller WHERE id='$caller_id'";
     $req = mysql_query($sql) or die("Sorry, SQL error");
     $res = mysql_num_rows($req);
     if($res == 1)
     {
      $sql = "DELETE from caller WHERE id='$caller_id'";
      $req = mysql_query($sql) or die("Sorry, SQL error");

      if($req != NULL)
      {
       $status = "Caller removed";
      }
     }
    }
   
    else if(isset($_POST['update']) and $caller_id != "new" and isset($_POST['blocked']) and isset($_POST['name']) and $_POST['name'] != "" and isset($_POST['number']) and $_POST['number'] != "" and isE164Number($_POST['number']))
    {
     $sql = "SELECT * from caller WHERE id='$caller_id'";
     $req = mysql_query($sql) or die("Sorry, SQL error");
     $res = mysql_num_rows($req);
     if($res == 1)
     {
      $caller_blocked = $_POST['blocked'];
      $caller_name = $_POST['name'];
      $caller_number = $_POST['number'];

      $sql = "UPDATE caller SET blocked='$caller_blocked', name='$caller_name', number='$caller_number' WHERE id='$caller_id'";
      $req = mysql_query($sql) or die("Sorry, SQL error");
      if($req != NULL)
      {
       $status = "Caller updated";
      }
     }
    }
    else if(isset($_POST['update']) and $caller_id == "new" and isset($_POST['blocked']) and isset($_POST['name']) and $_POST['name'] != "" and isset($_POST['number']) and $_POST['number'] != "" and isE164Number($_POST['number']))
    {
     $caller_blocked = $_POST['blocked'];
     $caller_name = $_POST['name'];
     $caller_number = $_POST['number'];

     $sql = "INSERT INTO caller(number, name, blocked) VALUES ('$caller_number', '$caller_name', '$caller_blocked');";
     $req = mysql_query($sql) or die("Sorry, SQL error");
     if($req != NULL)
     {
      $status = "Caller added";
     }
    }
    else
    {
     $error = "Missing information for this caller change";
    }
   }
  }
 }   

 echo '<html><head><title>Caller name/blocker</title>';
 echo '<link rel="stylesheet" type="text/css" href="./caller.css">';
 echo '</head><body>';

 if(isset($error))
 {
  echo "<br><table class=\"errortable\"><tr><td class=\"errortext\">$error</td></tr></table><br>\n";
 }
 if(isset($status))
 {
  echo "<br><table class=\"correcttable\"><tr><td class=\"correcttext\">$status</td></tr></table><br>\n";
 }

 echo "Hello, ".$auth->getLogin()."<br><br>" ;


 $sql = "SELECT * FROM caller";
 $req = mysql_query($sql) or die("DB ERROR");
 $res = mysql_num_rows($req);

 if($res > 0)
 {
  echo '<table width="500" border="0">';
  echo '<tr><td align="center">Phone Number</td><td align="center">Name</td>';
  echo '<td align="center">Blocked?</td><td align="center" width="135">Actions</td></tr>';
  while($data = mysql_fetch_assoc($req))
  {
   echo '<form method="post" action=""><input type="hidden" name="type" value="changecaller" />';
   echo '<input type="hidden" name="id" value="'.$data['id'].'" />';
   echo '<tr><td adlign="center"><input type="text" name="number" value="';
   echo $data['number'].'" placeholder="Phone Number" size="15"></td>';
   echo '<td align="center"><input type="text" name="name" value="';
   echo $data['name'].'" placeholder="Name" /></td><td align="center">';
   echo '<select name="blocked"><option value="0"';
   echo (!$data['blocked']?" selected":"").'>No</option><option value="1"';
   echo ($data['blocked']?" selected":"").'>Yes</option></select></td>';
   echo '<td><input type="submit" value="Update" name="update" /> ';
   echo '<input type="submit" value="Remove" name="remove" /></td></tr></form>';
  }

  echo '<tr><td></td><td></td><td></td><td align="center"><form method="post" action="">';
  echo '<input type="submit" value="New Number" name="newcaller" /></form></td></tr>';
  echo "</table>";
 }
 else
 {
  echo "no entry<br><br>";
  echo '<form method="post" action=""><input type="submit" value="New Caller" name="newcaller" /></form>';
 }

 if(isset($_POST['newcaller']))
 {
  echo "<table width=\"500\" border=\"0\"><tr><td align=\"center\"><u>Phone Number</u></td><td align=\"center\"><u>Name</u></td>";
  echo "<td align=\"center\"><u>Blocked?</u></td><td align=\"center\" width=\"135\"><u>Actions</u></td></tr>";
  echo '<form method="post" action=""><input type="hidden" name="type" value="changecaller" />';
  echo '<input type="hidden" name="id" value="new" />';
  echo "<tr><td align=\"center\"><input type=\"text\" name=\"number\" value=\"\" placeholder=\"Phone Number\" size=\"15\"/>";
  echo "</td><td align=\"center\"><input type=\"text\" name=\"name\" value=\"\" placeholder=\"Description\" />" ;
  echo "</td><td align=\"center\"><select name=\"blocked\">";
  echo "<option value=\"1\">Yes</option>";
  echo "<option value=\"0\" selected>No</option></select>";
  echo "</td><td align=\"center\">";
  echo '<input type="submit" value="Add" name="update" /> <input type="submit" value="Remove" name="remove" /></form></td></tr>';
  echo "</table><br>";
 }

 echo "<br><a href=\"?is_exit=1\">Exit</a>"; //Exit button
}

?>
