<?php

//set up includes
include('./inc/db.class.php');
include('./inc/client.class.php');
include('./inc/extra.class.php');

//set up classes
$db = new db();
$client_class = new clients($db->connection);
session_start();

//lock out
if(!isset($_SESSION['scout_session']) and !isset($_SESSION['client_id'])) {
	header("Location: ./index.php");
}
//set values
$limit1 = "0";
	if (isset($_GET['limit1'])) $limit1 = $_GET['limit1'];
$limit2 = "10";
	if (isset($_GET['limit2'])) $limit2 = $_GET['limit2'];
$form_badge = "0";
	if (isset($_GET['badge_select'])) $form_badge = $_GET['badge_select'];
$district = ""; 
	if (isset($_GET['district_id'])) $district = $_GET['district_id'];
$troop = "";
	if (isset($_GET['troop_id'])) $troop = $_GET['troop_id'];
$council = "";
	if (isset($_GET['council_id'])) { 
	    $council = $_GET['council_id'];
		if($council == "YES") { $form_yes = "selected='selected'"; $form_no = "";}
		else { $form_yes = ""; $form_no = "selected='selected'";}
		 }
	

//delete 
if(isset($_GET['delete_id']) and $_GET['delete_id'] <> "") {
$data = array();
$data['list_id'] = $_GET['delete_id'];
$view_list = $client_class->archiveClient($data);	
}
//start build list
$data = array();
$data['limit1'] = $limit1;
$data['limit2'] = $limit2;
$data['badge_id'] = $form_badge;
$data['district_id'] = $district;
$data['troop_id'] = $troop;
$data['council_id'] = $council;
$view_list = $client_class->listClients($data);
//page controls
$page = "./log_home.php";
$getstr= "badge_select=$form_badge&district_id=$district&troop_id=$troop";
$data2 = array();
$data2['badge_id'] = $form_badge;
$data2['district_id'] = $district;
$data2['troop_id'] = $troop;
$data2['council_id'] = $council;
$total = $client_class->listCount($data2);
$page_nav = paginate($page,$getstr,$limit1,$limit2,$total);
//create list
$member_list = "<table width='70%' ><tr>";
if($view_list<>"") {
		foreach($view_list as $k=>$v) { 
		  // add badges
		  $badges = "<textarea rows='7' cols='50%' class='badge_text' readonly>";
		  $data3 = array();
		  $data3['list_id'] = $v['list_id'];
          $badge_train = $client_class->viewLinkBadges($data3);
		  foreach($badge_train as $k2=>$v2) { $badges .= "$v2[badge_name]\n"; }
		  $badges .= "</textarea>";
		  //end badges
		  $today = date("Y-m-d");
		  if($v['council']=="YES") {$stamp = "<img src='./img/caution.png' width='20' height='20' valign='bottom' />"; } else {$stamp = "";}
		   if($v['ytp_exp']<=$today) {$stamp_ex = "<img src='./img/alert.png' width='20' height='20' align='bottom' />"; } else {$stamp_ex = "";}
		  $member_list .= "<td class='small_text' width='50%'>$stamp $v[first_name] $v[last_name] <br/>Phone: $v[phone]<br/>Email: $v[email]<br/><br/>District: $v[district_id]<br/>Unit: $v[troop_id]<br/>$stamp_ex YTP Exp: $v[ytp_exp] </br></td><td class='badge_text'>$badges</td><td  nowrap><img src='./img/edit_icon.ico' width='20' height='20' align='bottom' onClick='editPost($v[list_id])'/>&nbsp;&nbsp; <img src='./img/sqdelete.png' width='20' height='20' align='bottom' onClick='deletePost(\"$v[list_id]\",\"$v[first_name] $v[last_name]\")'/> </td></tr><tr><td colspan='3'><hr></td></tr>";
		
	}
$member_list .= "</table>";
    
} else {   }
// end build list
//build main
$main_content = "<table width='100%'>
                   <tr>
				     <td> $member_list <br/>$page_nav  </td>
				   </tr>
				 </table>";
				
//form stuff
    $data = array();
    $agentlist = $client_class->viewBadges($data);
	$badge_select = "<select name='badge_select' id='badge_select'>";
	$badge_select .= "<option value='' >Select</option>";
	foreach ($agentlist as $k=>$v) {
	 if($_GET['badge_select'] == $v['badge_id'] and $_GET['badge_select'] != '') { $pick_select = "selected ='selected'"; } else { $pick_select = ""; }
		$badge_select .= "<option value='$v[badge_id]' $pick_select>$v[badge_name]</option>";
	}
	$badge_select .= "</select>";


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
<script>
function deletePost(list_id,list_name) {
    var ask = window.confirm("Confirm Delete for "+list_name);
    if (ask) {window.location.href = "./log_home.php?delete_id="+list_id;
        
    
window.alert("This post was successfully deleted.");
    }
}
function editPost(list_id) {
    
	window.open("./trainers_edit.php?edit_id="+list_id);
}

</script>
<link href="./inc/scoutstyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include("./inc/header.php"); ?>
<table bgcolor="#FFFFFF" height="500" width="100%"  align="center" cellpadding="2"> 
  <tr>
    <td class="med_text"> <form name="form1" method="get" action="">
    District:<input name="district_id" type="text" value="<?php echo $district; ?>"/>
     Unit:
     <input name="troop_id" type="text" value="<?php echo $troop; ?>"/>
          Badge:<?php echo $badge_select; ?> Include Council: <select name="council_id">
            <option <?php echo $form_yes; ?> value="YES">Yes</option>
            <option <?php echo $form_no; ?> value="NO">No</option>
        </select>
          <input type="submit" name="button" id="button" value="Submit"></form></td>
  </tr>  
  <tr>
    <td valign="top" width="100%" class="small_text">
     <?php echo $main_content; ?><br/>
     <img src='./img/caution.png' width='15' height='15' valign='bottom' /> = Counsel <img src='./img/alert.png' width='15' height='15' align='bottom' /> = Expired <img src='./img/edit_icon.ico' width='15' height='15' align='bottom'/> = Edit <img src='./img/sqdelete.png' width='15' height='15' align='bottom'/> = Delete
    </td>
   </tr>
</table>
<?php include("./inc/footer.php"); ?>
</body>
</html>
