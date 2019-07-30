<?php
$cell_class = "form_button_white";

if ($_SESSION['scout_session'] == "YES") {
    $login_form = "<span class='white_footer'>Welcome $_SESSION[scout_user]</span>";
    $logout_form = "<div class='$cell_class' onclick=\"go('./index.php?logout_session=YES');\">Logout</div>";
    $error_text = "";
    $login_error = 0;
    $top_nav = "<div class='$cell_class' onclick=\"go('./log_home.php?');\">Search</div>";

    // If the user is an Administrator, then let them do more things.
    if ($_SESSION['client_id'] > 0 and $_SESSION['role'] == "Administrator") {
        $top_nav .= " <div class='$cell_class' onclick=\"go('./trainers.php?');\">Add New</div>
        <div class='$cell_class' onclick=\"go('./admin.php?');\">Admin</div>";
    }

} else {
    $error_text = "";
    if ($_GET['login_error'] == "YES") {$error_text = "<span class='small_red_text'>You have been logged out</span><br>";}
    if ($login_error == "YES") {$error_text = "<span class='small_red_text'>Wrong Email and Password Combo Please try again</span><br>";}

    $login_form = "$error_text<form action='./index.php' method='post' enctype='application/x-www-form-urlencoded' name='log_form'><span class='white_footer'>Email:</span><input name='l_email' type='text' size='15' class='small_text'/><span class='white_footer'>Password:</span><input name='l_password' type='password' size='15' class='small_text'/><input name='login_session' type='hidden' value='YES' />
              <input type='submit' class='$cell_class' name='login' value='Login'/></form>";
    $logout_form = "";
    $top_nav = "";
}
?>


<table width="100%" align="center" bgcolor="#184F92">
  <tr >
    <td class="white_headline"><img src="./img/scout_logo.jpg" width="75" height="75" /> Badge Counselor Database</td>
    <td width="50%" align="right" valign="top">
	 <?php echo $login_form; ?><br/></td>
  </tr>
</table>
<div class="bubble-nav">
<table width="100%">
  <tr>
    <td width="80%" valign="top" nowrap="nowrap">
     <div class="<?php echo $cell_class; ?>" onclick="go('./index.php?page=home');">Home</div>
	 <?php echo $top_nav; ?>
    <?php echo $logout_form; ?>
     </td>
    <td align="right" nowrap="nowrap">

    </td>
  </tr>
</table>
</div>
