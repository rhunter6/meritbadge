<?php
session_start();
//set up includes
include 'inc/db.class.php';
include 'inc/client.class.php';

//set up classes
$db = new db();
$client_class = new clients($db->connection);

var_dump($_GET);

// See if the user logged out.  Login_error=YES means show logout message.
if ($_GET['logout_session'] == "YES") {
    session_destroy();
    header("Location: index.php?login_error=YES");
}

// See if the user was able to log in correctly.
if ($_POST['login_session'] == "YES") {
    $data = array();
    $data['email'] = $_POST['l_email'];
    $data['password'] = md5($_POST['l_password']);
    $login_user = $client_class->getAdmin_login($data);
    // $login_error flags if the user was able to login correctly or they typed something wrong.
    if ($login_user['client_id'] >= 1) {
        $_SESSION['scout_user'] = $login_user['first_name'] . " " . $login_user['last_name'];
        $_SESSION['client_id'] = $login_user['client_id'];
        $_SESSION['scout_session'] = "YES";
        $_SESSION['role'] = $login_user['role'];
        $login_error = "NO";
    } else {
        $login_error = "YES";
    }
}

// Create message
if ($_SESSION['scout_session'] === "YES") {
    $message = 'Click search button to start.';
} else {
    $message = 'Please login to access.';
}

$main_content = "<table width='100%' class='small_text' cellpadding='1'>
                   <tr>
                     <td align='center' valign='middle' class='large_text'>
                        <br/><br/>Welcome to the Boy Scouts<br/>Counselor Database<br/><br/>
                        <img src='./img/scout_logo.jpg' width='200' height='200' />
                        <br/><br/>" . $message . "
					 </td>
				   </tr>
				 </table>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="scout">
<title>Boy Scout Badge Data</title>
<script src="./inc/scoutscripts.js"></script>
<link href="./inc/scoutstyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "inc/header.php";?>
<table bgcolor="#FFFFFF" height="500" width="100%"  align="center" cellpadding="2">
  <tr>
    <td valign="top" width="100%">
     <?php echo $main_content; ?><br/>
    </td>
   </tr>
</table>
<?php include "inc/footer.php";?>
</body>
</html>
