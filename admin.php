<?php
//set up includes
include 'inc/db.class.php';
include 'inc/client.class.php';
include './inc/extra.class.php';
//set up classes
$db = new db();
$client_class = new clients($db->connection);
session_start();

//lock out
if (!isset($_SESSION['scout_session'])
    and !isset($_SESSION['client_id'])
    and $_SESSION['role'] !== "Administrator") {
    header("Location: ./index.php");
}
//delete
if (isset($_GET['delete_id']) and $_GET['delete_id'] != "") {
    $data = array();
    $data['client_id'] = $_GET['delete_id'];
    $delete_list = $client_class->archiveAdmin($data);
}

//add user
if ($_POST['add_admin'] == "YES") {

    $errors = "";
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors .= "E-mail is not valid<br/>";
    }
    if (empty($_POST['first_name'])) {
        $errors .= "First Name is not valid<br/>";
    }
    if (empty($_POST['last_name'])) {
        $errors .= "Last Name is not valid<br/>";
    }
    if (empty($_POST['password'])) {
        $errors .= "Password is not valid<br/>";
    }
    if (empty($_POST['role'])) {
        $errors .= "Role is not valid<br/>";
    }

    if (empty($errors)) {
        $data = array();
        $data['email'] = $_POST['email'];
        $data['first_name'] = $_POST['first_name'];
        $data['last_name'] = $_POST['last_name'];
        $data['password'] = $_POST['password'];
        $data['role'] = $_POST['role'];
        $edit_done = $client_class->addAdmin($data);
        //echo $edit_done;
        if ($edit_done != "EXIST") {
            $goto_url = "admin.php?page_result=$edit_done";
            header("Location: $goto_url");
        }
    }
}

//signup form

if ($edit_done == "EXIST") {
    $error_dup = "</br>Sorry that email is already is use.";
}
//Set default role
if (!isset($_POST['role'])) {
    $_POST['role'] = "Viewer";
}

//create form
$signup_form = "
<table >
  <tr>

    <td valign='top'>
<form action=\"admin.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\" name=\"add_admin\">
    <table class=\"bubble\" width=\"400\" cellpadding='2'>
        <tr>
          <td colspan='2'>
              <div style='text-align:center' class='bubble2'>
               Add Admin<br/> <br/>All fields required.<br/><span class='small_red_text'>$errors $error_dup</span>
              </div>
              <hr />
            </td>
          </tr>
    <tr>
      <td align=\"right\">First Name:</td>
      <td valign='top'><input name=\"first_name\" type=\"text\" value='$_POST[first_name]'/></td>
     </tr>
      <tr>
      <td align=\"right\">Last Name:</td>
      <td><input name=\"last_name\" type=\"text\" value='$_POST[last_name]'/></td>
     </tr>
     <tr>
      <td align=\"right\">Email:</td>
      <td><input name=\"email\" type=\"text\" value='$_POST[email]'/></td>
     </tr>
    <tr>
      <td align=\"right\">Password:</td>
      <td><input name=\"password\" type=\"password\" value='$_POST[password]'/></td>
    </tr>
    <tr>
      <td align=\"right\">Role:</td>
      <td>
        <input name=\"role\" type=\"radio\" value=\"Administrator\" ";

if ($_POST['role'] == "Administrator") {
    $signup_form .= "checked";
}

$signup_form .= " >Administrator
        <input name=\"role\" type=\"radio\" value=\"Viewer\" ";

if ($_POST['role'] == "Viewer") {
    $signup_form .= "checked";
}

$signup_form .= " >Viewer
      </td>
    </tr>
    <tr>
      <td colspan='2' align='center'>
        <input name='ip_address' type='hidden' value='$_SERVER[REMOTE_ADDR]' />
        <input name='add_admin' type='hidden' value='YES' />
        <input type='submit' style='width:100px;' name='register' class='form_button' value='Register'/>
      </td>
	</tr>
</table>
  </td>
 </tr>
</table>
    </form>";
//end form

$admin_list = $client_class->listAdmin($data);
foreach ($admin_list as $d => $e) {
    $user_list .= "<img src='./img/sqdelete.png' width='20' height='20' align='bottom' onClick='deleteUser(\"$e[client_id]\", \"$e[first_name] $e[last_name]\")'/>
        $e[first_name] $e[last_name] ($e[email]) - $e[role]</br><hr></br>";

}
//main content
$main_content = "<table width='100%' class='small_text' cellpadding='1'>
                   <tr>
				     <td width='40%' align='justify' valign='top'> $signup_form
					 </td>
					 <td width='60%' align='left' valign='top'>$user_list
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
<meta name="author" content="scouts">
<title>Boy Scout Badge Admin</title>
<script src="./inc/scoutscripts.js"></script>
<script>
function deleteUser(client_id, client_name) {
    var ask = window.confirm("Confirm delete for " + client_name);
    if (ask) {
        window.location.href = "./admin.php?delete_id=" + client_id;
        window.alert("User " + client_name + " was successfully deleted.");
    }
}
</script>
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