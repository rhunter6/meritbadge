<?php

//set up includes
include './inc/db.class.php';
include './inc/client.class.php';
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

//add user
if ($_POST['add_client'] == "YES") {
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
    if (empty($_POST['phone'])) {
        $errors .= "Phone is not valid<br/>";
    }
    if (empty($_POST['district_id'])) {
        $errors .= "District is not valid<br/>";
    }
    if (empty($_POST['ytp_exp'])) {
        $errors .= "YTP Exp is not valid<br/>";
    }
    if (empty($_POST['council'])) {
        $errors .= "Council is not valid<br/>";
    }
    if (isset($errors) and $errors != "") {
        $errors .= "</br>You Must Reselect All Badges<br/>";
    }

    if (empty($errors)) {
        $data = array();
        $data['email'] = $_POST['email'];
        $data['first_name'] = $_POST['first_name'];
        $data['last_name'] = $_POST['last_name'];
        $data['phone'] = $_POST['phone'];
        $data['district_id'] = $_POST['district_id'];
        $data['troop_id'] = $_POST['troop_id'];
        $data['ytp_exp'] = $_POST['ytp_exp'];
        $data['council'] = $_POST['council'];
        $edit_done = $client_class->addClient($data);
        //echo $edit_done;
        if (isset($edit_done) and $edit_done != "EXIST") {
            foreach ($_POST['sel'] as $selectedOption) {
                $data_b = array();
                $data_b['t_main_id'] = $edit_done;
                $data_b['t_badge_id'] = $selectedOption;
                $add_badge = $client_class->addBadges($data_b);
                //echo $selectedOption."\n";
            }

        }

        if ($edit_done != "EXIST") {
            $goto_url = "trainers.php?page_result=$edit_done";
            header("Location: $goto_url");
        } else {
            // $goto_url = "trainers.php?page_result=$edit_done";
            //header("Location: $goto_url");
        }
    }
}

//signup form

if ($edit_done == "EXIST") {
    $error_dup = "</br>Sorry that email is already is use.</br>You Must Reselect All Badges";
}
// badge list start
$data = array();
$badge_options = $client_class->viewBadges($data);
$ct = 0;
if ($badge_options != "") {
    $amen_list .= "Qualified Badges<br/>Control+ Click for Multiple Selections<br/><select name='sel[]' multiple title='selections' onfocus='this.size=10;' onblur='this.size=10;'
        onchange='this.size=10; this.blur();'>";
    foreach ($badge_options as $d => $e) {
        $amen_list .= "<option value='$e[badge_id]'>$e[badge_name]</option>";
    }
}

$amen_list .= "</select>";
//badge list end
//create form
$signup_form = "
<table >
  <tr>

    <td valign='top'>
<form action=\"trainers.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\" name=\"add_user\">
    <table class=\"bubble\" width=\"400\" cellpadding='2'>
        <tr>
          <td colspan='2'>
              <div style='text-align:center' class='bubble2'>
               Add Counselor<br/> <br/>All fields required.<br/><span class='small_red_text'>$errors $error_dup</span>
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
      <td align=\"right\">Phone:</td>
      <td><input name=\"phone\" type=\"phone\" value='$_POST[phone]'/></td>
    </tr>
    <tr>
      <td align=\"right\">District:</td>
      <td><input name=\"district_id\" type=\"text\" value='$_POST[district_id]'/></td>
    </tr>
    <tr>
      <td align=\"right\">Unit:</td>
       <td><input name=\"troop_id\" type=\"text\" value='$_POST[troop_id]'/></td>
     </tr>
    <tr>
      <td align=\"right\">YTP Expiration:</td>
      <td><input name=\"ytp_exp\" type=\"text\" value='$_POST[ytp_exp]' id='datepicker'/></td>
    </tr>
	<tr>
	  <td align=\"right\">Council</td>
	  <td><select name='council'><option value='NO'>NO</option><option value='YES'>YES</option></select></td>
	</tr>
	<tr>
	  <td align=\"center\" colspan='2'>$amen_list</td>
	</tr>
	<tr>
	  <td colspan='2' align='center'><input name='ip_address' type='hidden' value='$_SERVER[REMOTE_ADDR]' /><input name='add_client' type='hidden' value='YES' /><input type='submit' style='width:100px;' name='register' class='form_button' value='Register'/></td>
	</tr>
</table>
  </td>
 </tr>
</table>
    </form>";

//end form

//start build list
$v = $client_class->viewClient($_GET['page_result']);
$member_list = "";
if ($v != "") {
    $badges = "";
    $data3 = array();
    $data3['list_id'] = $v['list_id'];
    $badge_train = $client_class->viewLinkBadges($data3);
    foreach ($badge_train as $k4 => $v4) {$badges .= "<br/>$v4[badge_name]";}
    $member_list .= "Last Entered:</br>$v[first_name] $v[last_name]<br/>Phone: $v[phone]<br/>Email: $v[email]<br/>Council: $v[council]<br/>District: $v[district_id]<br/>Troop: $v[troop_id]<br/>YTP Exp: $v[ytp_exp] $badges <br/><hr width='100%' /><br/>";

} else {}
// end build list
//start main content
$main_content = "<table width='100%' class='small_text' cellpadding='1'>
                   <tr>
				     <td align='justify' valign='top'> $signup_form <br/> $member_list
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
<title>Boy Scout Add Counselor</title>
<script src="./inc/scoutscripts.js"></script>
<link href="./inc/scoutstyle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd'});
  });
  </script>
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
