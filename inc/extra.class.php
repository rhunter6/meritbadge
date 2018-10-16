<?php


//extra functions
function paginate($page,$getstr,$limit1,$limit2,$total){

	$selected = array();
	$selected[$limit2] = " selected = 'selected' ";

	$s="<select onchange=\"window.location='$page?limit1=$limit1&limit2='+this.value+'&$getstr' \" >";
	$s.="<option value=10 $selected[10]>10 / Page</option>";
	$s.="<option value=20 $selected[20]>20 / Page</option>";
	$s.="<option value=50 $selected[50]>50 / Page</option>";
	$s.="<option value=100 $selected[100]>100 / Page</option>";
	$s.="<option value=500 $selected[500]>500 / Page</option>";
	$s.="<option value=5000 $selected[5000]>All</option>";
	$s.="</select>";


	if ($limit1 > 0) {
		$newlimit1 = $limit1 - $limit2;
		if ($newlimit1 < 0) $newlimit1 = 0;
		$prev = "<a href='$page?limit1=0&limit2=$limit2&$getstr'><strong>&laquo;&laquo;</strong></a> <a href='$page?limit1=$newlimit1&limit2=$limit2&$getstr'><strong>&laquo; Prev</strong></a>";
	}	
	
	
	

	if ($limit1 + $limit2 < $total) {
		$newlimit1 = $limit1 + $limit2;
		$maxlimit = $total - $limit2;
		$next = "<a href='$page?limit1=$newlimit1&limit2=$limit2&$getstr'><strong>Next &raquo;</strong></a> <a href='$page?limit1=$maxlimit&limit2=$limit2&$getstr'><strong>&raquo;&raquo;</strong></a>";
	}	
	
	if ($limit1 + $limit2 > $total) $end = $total;
	else $end = $limit1 + $limit2;
	
	$limit1 = $limit1 + 1; // don't like to show 0
	
	$p = "$prev $limit1 - $end of $total $next &nbsp; $s ";

	
	return $p;


}


function dates_interconv( $date_format1, $date_format2, $date_str )
{ 

	   if (! (strlen($date_str)>0) ) return "";
	   if ($date_str == "0000-00-00") return "";
       $base_struc    = split('[/.-]', $date_format1);
       $date_str_parts = split('[/.-]', $date_str );      
       $date_elements = array();
       $p_keys = array_keys( $base_struc );
       foreach ( $p_keys as $p_key )
       {
           if ( !empty( $date_str_parts[$p_key] ))
           {
               $date_elements[$base_struc[$p_key]] = $date_str_parts[$p_key];
           }
           else return false;
       }
       $dummy_ts = mktime( 0,0,0, $date_elements['m'],$date_elements['d'],$date_elements['Y']);
       return date( $date_format2, $dummy_ts );
}

function makeStamp($theString) {
  if (ereg("([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})", $theString, $strReg)) {
    $theStamp = mktime($strReg[4],$strReg[5],$strReg[6],$strReg[2],$strReg[3],$strReg[1]);
  } else if (ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})", $theString, $strReg)) {
    $theStamp = mktime(0,0,0,$strReg[2],$strReg[3],$strReg[1]);
  } else if (ereg("([0-9]{2}):([0-9]{2}):([0-9]{2})", $theString, $strReg)) {
    $theStamp = mktime($strReg[1],$strReg[2],$strReg[3],0,0,0);
  }
  return $theStamp;
}

function makeDateTime($theString, $theFormat) {
  $theDate=date($theFormat, makeStamp($theString));
  return $theDate;
}


function getStateList($skipfirst = true){

$state_list = array();
if (!$skipfirst) $state_list['Select']="Select State";
$state_list['']="";
$state_list['AL']="Alabama";
$state_list['AK']="Alaska";
$state_list['AZ']="Arizona";
$state_list['AR']="Arkansas";
$state_list['CA']="California";
$state_list['CO']="Colorado";
$state_list['CT']="Connecticut";
$state_list['DE']="Delaware";
$state_list['DC']="District Of Columbia";
$state_list['FL']="Florida";
$state_list['GA']="Georgia";
$state_list['HI']="Hawaii";
$state_list['ID']="Idaho";
$state_list['IL']="Illinois";
$state_list['IN']="Indiana";
$state_list['IA']="Iowa";
$state_list['KS']="Kansas";
$state_list['KY']="Kentucky";
$state_list['LA']="Louisiana";
$state_list['ME']="Maine";
$state_list['MD']="Maryland";
$state_list['MA']="Massachusetts";
$state_list['MI']="Michigan";
$state_list['MN']="Minnesota";
$state_list['MS']="Mississippi";
$state_list['MO']="Missouri";
$state_list['MT']="Montana";
$state_list['NE']="Nebraska";
$state_list['NV']="Nevada";
$state_list['NH']="New Hampshire";
$state_list['NJ']="New Jersey";
$state_list['NM']="New Mexico";
$state_list['NY']="New York";
$state_list['NC']="North Carolina";
$state_list['ND']="North Dakota";
$state_list['OH']="Ohio";
$state_list['OK']="Oklahoma";
$state_list['OR']="Oregon";
$state_list['PA']="Pennsylvania";
$state_list['RI']="Rhode Island";
$state_list['SC']="South Carolina";
$state_list['SD']="South Dakota";
$state_list['TN']="Tennessee";
$state_list['TX']="Texas";
$state_list['UT']="Utah";
$state_list['VT']="Vermont";
$state_list['VA']="Virginia";
$state_list['WA']="Washington";
$state_list['WV']="West Virginia";
$state_list['WI']="Wisconsin";
$state_list['WY']="Wyoming";

	return $state_list;

}

function state_selection($current_state = "", $select_name = "state", $class = "", $more = "", $display = "long", $id = 'state') {
    
	$current_state = strtoupper($current_state);

	if($select_name != '') { $a = $select_name; } else { $a = "state"; }	
	
	
	$c = $current_state;
	
	    $state_drop = "<select name='$a' id='$id' class = '$class' $more>";
		
		$state_list = getStateList();
		
		foreach ($state_list as $k=>$v) {
		    if($c == $k and $k != '') { $state_select = "selected ='selected'"; } 
			else { $state_select = ""; }
			
			if ($display == "short") {
				if ($k == '') $statename = "  ";
				else $statename = $k;				 
			}	
			else $statename = $v;
			$state_drop .=  "<option value='$k' $state_select >$statename</option>";
		}
	$state_drop .=  "</select>";
	return $state_drop;
}
//$current_state = "TX";
//echo state_selection($current_state, $select_name);

function month_selection($current_month, $select_name, $class, $fun = "") {
    
	if ($fun != "") $fun = "onchange='$fun'";
	
	if($select_name != '') { $a = $select_name; } else { $a = "month"; }
	$c = $current_month;
	
	    $month_drop = "<select name='$a' id='$a' class = '$class' $fun>";
		$month_list = array(
		        '0'=>"MM",
				'01'=>"Jan",
                '02'=>"Feb", 
                '03'=>"Mar", 
                '04'=>"Apr", 
                '05'=>"May", 
                '06'=>"Jun", 
                '07'=>"Jul", 
                '08'=>"Aug", 
                '09'=>"Sep", 
                '10'=>"Oct", 
                '11'=>"Nov", 
                '12'=>"Dec");
		foreach ($month_list as $k=>$v) {
		    if($c == $k and $k != '') { $month_select = "selected ='selected'"; } 
			else { $month_select = ""; }
			$month_drop .=  "<option value='$k' $month_select >$v</option>";
		}
	$month_drop .=  "</select>";
	return $month_drop;
}
//$current_month = "05";
//echo month_selection($current_month, $select_name);

function day_selection($current_day, $select_name, $class, $fun = "") {
    
	if ($fun != "") $fun = "onchange='$fun'";
	
	if($select_name != '') { $a = $select_name; } else { $a = "day"; }
	$c = $current_day;
	
	    $day_drop = "<select name='$a' id='$a' class = '$class' $fun>";
		$day_list = array(
		        '0'=>"DD",
				'01'=>"01",
                '02'=>"02", 
                '03'=>"03", 
                '04'=>"04", 
                '05'=>"05", 
                '06'=>"06", 
                '07'=>"07", 
                '08'=>"08", 
                '09'=>"09", 
                '10'=>"10", 
                '11'=>"11", 
                '12'=>"12",
				'13'=>"13",
				'14'=>"14",
				'15'=>"15",
				'16'=>"16",
				'17'=>"17",
				'18'=>"18",
				'19'=>"19",
				'20'=>"20",
				'21'=>"21",
				'22'=>"22",
				'23'=>"23",
				'24'=>"24",
				'25'=>"25",
				'26'=>"26",
				'27'=>"27",
				'28'=>"28",
				'29'=>"29",
				'30'=>"30",
				'31'=>"31");
		foreach ($day_list as $k=>$v) {
		    if($c == $k and $k != '') { $day_select = "selected ='selected'"; } 
			else { $day_select = ""; }
			$day_drop .=  "<option value='$k' $day_select >$v</option>";
		}
	$day_drop .=  "</select>";
	return $day_drop;
}
//$current_day = "05";
//echo day_selection($current_day, $select_name);



function year_selection($start_year, $end_year, $current_year, $select_name, $class, $fun = "") {
    
	if ($fun != "") $fun = "onchange='$fun'";
	if($select_name != '') { $a = $select_name; } else { $a = "year"; }
	$c = $current_year;
	if($start_year != '') { $start = $start_year; } else { $start = "1950"; }
	if($end_year != '') { $end = $end_year; } else { $end = date("Y"); }
	
	    $year_drop = "<select name='$a' id='$a' class = '$class' $fun>";
		$year_drop .= "<option value='0' $year_select >YYYY</option>";
		while($start <= $end) {
		    if($c == $start and $start != '') { $year_select = "selected ='selected'"; } 
			else { $year_select = ""; }
			$year_drop .=  "<option value='$start' $year_select >$start</option>";
			$start = $start + 1;
		}
	$year_drop .=  "</select>";
	return $year_drop;
}
//$start_year = "1950";
//$end_year = "2009";
//$current_year = "1980";
//echo year_selection($start_year, $end_year, $current_year, $select_name);

//clean text
function cleanText($str){

$str = str_replace("Ñ" ,"&#209;", $str);
//$str =  preg_replace('/Ñ/g',"|&#209;|", $str);

//echo "Text BEGIN ".$str."  --- ".bin2hex ("Ñ")."\n<BR>";     // d1

/*
for($i = 0 ; $i < strlen($str) ; $i++){
echo "".$str{$i}."  - ". bin2hex ( $str{$i})."<BR>";
}
*/

$str = str_replace("ñ" ,"&#241;", $str);
$str = str_replace("ñ" ,"&#241;", $str);
$str = str_replace("Á","&#193;", $str);
$str = str_replace("á","&#225;", $str);
$str = str_replace("É","&#201;", $str);
$str = str_replace("é","&#233;", $str);

$str = str_replace("ú","&#250;", $str);

$str = str_replace("ù","&#249;", $str);
$str = str_replace("Í","&#205;", $str);
$str = str_replace("í","&#237;", $str);
$str = str_replace("Ó","&#211;", $str);
$str = str_replace("ó","&#243;", $str);
$str = str_replace("\"","&#8220;", $str);

$str = str_replace("\"","&#8221;", $str);

$str = str_replace("'","&#8216;", $str);
$str = str_replace("'","&#8217;", $str);
$str = str_replace("—","&#8212;", $str);

$str = str_replace("–","&#8211;", $str);
$str = str_replace("™","&trade;", $str);
$str = str_replace("ü","&#252;", $str);
$str = str_replace("Ü","&#220;", $str);
$str = str_replace("Ê","&#202;", $str);
$str = str_replace("ê","&#238;", $str);
$str = str_replace("Ç","&#199;", $str);
$str = str_replace("ç","&#231;", $str);
$str = str_replace("È","&#200;", $str);
$str = str_replace("è","&#232;", $str);
$str = str_replace("•","&#149;" , $str);

return $str;

}






?>
