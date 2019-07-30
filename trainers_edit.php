<?php

//set up includes
include('./inc/db.class.php');
include('./inc/client.class.php');
include('./inc/extra.class.php');
include("inc/Sajax.php");

//set up classes
$db = new db();
$client_class = new clients($db->connection);
session_start();

//lock out
if(!isset($_SESSION['scout_session']) and !isset($_SESSION['client_id']) and $_SESSION['role'] !== "Administrator") {
	header("Location: ./index.php");
}

//add user
	if($_POST['add_client']=="YES") {
	
	$errors = "";
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors .= "E-mail is not valid<br/>";
	}
	if(empty($_POST['first_name'])) { $errors .= "First Name is not valid<br/>";}
	if(empty($_POST['last_name'])) { $errors .= "Last Name is not valid<br/>";} 
	if(empty($_POST['phone'])) { $errors .= "Phone is not valid<br/>";} 
	if(empty($_POST['district_id'])) { $errors .= "District is not valid<br/>";} 
	if(empty($_POST['troop_id'])) { $errors .= "Troop is not valid<br/>";}
	if(empty($_POST['ytp_exp'])) { $errors .= "YTP Exp is not valid<br/>";} 
	if(empty($_POST['council'])) { $errors .= "Council is not valid<br/>";}
			
	if(empty($errors)) {	
	$data = array();
	$data['email'] = $_POST['email'];
	$data['first_name'] = $_POST['first_name'];
	$data['last_name'] = $_POST['last_name'];
	$data['phone'] = $_POST['phone'];
	$data['district_id'] = $_POST['district_id'];
	$data['troop_id'] = $_POST['troop_id'];
	$data['ytp_exp'] = $_POST['ytp_exp'];
	$data['council'] = $_POST['council'];
	$data['list_id'] = $_POST['list_id'];
	$edit_done = $client_class->editClient($data);
	//echo $edit_done;
	//if(isset($edit_done) and $edit_done<>"EXIST") {	
	  //foreach($_POST['sel'] as $selectedOption) {
     // $data_b = array();
	  //$data_b['t_main_id'] = $edit_done;
	 // $data_b['t_badge_id'] = $selectedOption;	
     // $add_badge = $client_class->addBadges($data_b);
	//echo $selectedOption."\n";
	//}
	
	//}
 
	
	
	if(empty($errors)) {
					
		 $goto_url = "trainers_edit.php?edit_id=$_POST[list_id]&page_result=UPDATED";
		 header("Location: $goto_url");
	  }
	  
	  
	 }
	}


//signup form			 
//start build list
function edit_user($client_id, $option_id, $yn){	

	if($yn == "true") { 
	
		$updateSQL = "INSERT INTO badge_links SET t_main_id='$client_id', t_badge_id='$option_id'"; 
	    $result = mysqli_query($updateSQL);
		return $elementid;     
		        
	} else { 
		$updateSQL = "DELETE FROM badge_links WHERE t_main_id='$client_id' and t_badge_id='$option_id'"; 
	    $result = mysqli_query($updateSQL);
		return $elementid;
	}

	         
}

$v = $client_class->viewClient($_GET['edit_id']);
if($v<>"" and $_GET['edit_id']<>"") {
		 
		  $badges = "";
		  $data3 = array();
		  $data3['list_id'] = $v['list_id'];
          $badge_train = $client_class->viewLinkBadges($data3);
		  if($badge_train!="NULL") {
		  foreach($badge_train as $k4=>$v4) { $badges[] = "$v4[badge_name]"; }
		  }

    
}  
// end build list

// badge list start
$data = array();
$badge_options = $client_class->viewBadges($data);

$ct = 0;
$amen_list = "<table cellpadding='1' width='100%'>";
if($badge_options!="") {
	
	    //$amen_list .= "Qualified Badges<br/>Click to add /remove <br/><select name='selections[]' multiple title='selections' size='18'>";
		foreach($badge_options as $d=>$e) { $ct++;
		  if($badges!="") {
		  if(in_array($e[badge_name],$badges)) { $checked = "checked='checked'"; $box_class = "class='check_box'"; } else  { $checked = ""; $box_class = "class='check_box'"; }
		  
		  }
	      //$amen_list .= "<option value='$e[badge_id]' onchange='edit_badge($_GET[edit_id],$e[badge_id],this)' $checked>$e[badge_name] </option>";
		  $amen_list .= "<td width='10%'><div id='edit_customer$e[badge_id]' align='left'>
		  <input name='edit_customer' type='checkbox' value='YES' onclick='edit_user($_GET[edit_id],$e[badge_id],this)' $checked $box_class/><span >$e[badge_name]</span> 
		  </div></td>";
		  if($ct == 4) { $amen_list .= "</tr><tr>"; $ct = 0; } 
		  
		}
}
		
			
	//$amen_list .= "</select>";
	$amen_list .= "</tr></table>";	
//badge list end
//create form
if($v['council']=="YES") { $select_y ="selected='selected'"; $select_n =""; } else { $select_y =""; $select_n ="selected='selected'"; }
if(isset($_GET['page_result'])) { $updated = "<br/>$_GET[page_result]<br/>"; }	 
$signup_form = "
<table >
  <tr>
  
    <td valign='top'>
<form action=\"trainers_edit_b.php?edit_id=$v[list_id]\" method=\"post\" enctype=\"application/x-www-form-urlencoded\" name=\"edit_user2\">
    <table class=\"bubble\" width=\"400\" cellpadding='2'>
        <tr>
          <td colspan='2'>
              <div style='text-align:center' class='bubble2'>
               Add Counselor<br/> <br/>All fields required.<br/><span class='small_red_text'>$errors $error_dup $updated</span>
              </div>
              <hr />
            </td>
          </tr>
    <tr>
      <td align=\"right\">First Name:</td>
      <td valign='top'><input name=\"first_name\" type=\"text\" value='$v[first_name]'/></td>
     </tr>
      <tr>
      <td align=\"right\">Last Name:</td>
      <td><input name=\"last_name\" type=\"text\" value='$v[last_name]'/></td>
     </tr>
     <tr>
      <td align=\"right\">Email:</td>
      <td><input name=\"email\" type=\"text\" value='$v[email]'/></td>
     </tr>
    <tr>
      <td align=\"right\">Phone:</td>
      <td><input name=\"phone\" type=\"phone\" value='$v[phone]'/></td>
    </tr>
    <tr>
      <td align=\"right\">District:</td>
      <td><input name=\"district_id\" type=\"text\" value='$v[district_id]'/></td>
    </tr>
    <tr>
      <td align=\"right\">Unit:</td>
       <td><input name=\"troop_id\" type=\"text\" value='$v[troop_id]'/></td>
     </tr>
    <tr>
      <td align=\"right\">YTP Expiration:</td>
      <td><input name=\"ytp_exp\" type=\"text\" value='$v[ytp_exp]' id='datepicker'/></td>
    </tr>
	<tr>
	  <td align=\"right\">Council</td>
	  <td><select name='council' ><option value='NO' $select_n>NO</option><option value='YES' $select_y>YES</option></select></td>
	</tr>
	<tr>
	  <td align=\"center\" colspan='2'></td>
	</tr>
	<tr>
	  <td colspan='2' align='center'><input name='list_id' type='hidden' value='$v[list_id]' /><input name='ip_address' type='hidden' value='$_SERVER[REMOTE_ADDR]' /><input name='add_client' type='hidden' value='YES' /><input type='submit' style='width:100px;' name='register' class='form_button' value='Register'/></td>
	</tr>
</table>
  </td>
 </tr>
</table>
    </form>";
			 
//end form




//start main content
$main_content = "<table width='100%' class='small_text' cellpadding='1'>
                   <tr>
				     <td width='20%' align='justify' valign='top'> $signup_form 
					 </td><td align='left' valign='top'> $amen_list
					 </td>
				     
				   </tr>
				 </table>";

sajax_init();	 
sajax_export("edit_user");
sajax_handle_client_request();
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
<script>
<? sajax_show_javascript(); ?>


function edit_user(client_id, option_id, yn) {			 
		
		 elementid = "edit_customer"+option_id;
		 elem = document.getElementById(elementid);
		 elem.style.backgroundColor = "#CAFF70";
			 
	     
		  x_edit_user(client_id, option_id, yn.checked , elementid, edit_user_callback); 
		
		}
		
function edit_user_callback(retval) {	
  			elem = document.getElementById(retval);
			elem.style.backgroundColor = "#00FF00";			 	 

 	}		
	
	
</script>
</head>
<body>
<?php include("inc/header.php"); ?>
<table bgcolor="#FFFFFF" height="500" width="100%"  align="center" cellpadding="2">
  <tr>
    <td valign="top" width="100%">
     <?php echo $main_content; ?><br/>
    </td>
   </tr>
</table>
<?php include("inc/footer.php"); ?>
</body>
</html>
