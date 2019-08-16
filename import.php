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
if (!isset($_SESSION['scout_session']) and !isset($_SESSION['client_id']) and $_SESSION['role'] !== "Administrator") {
    header("Location: ./index.php");
}

$row = 0;
$data2 = array();
if (($handle = fopen("./inc/Merit.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $num = count($data);
        $row++;
        $line = 0;
        $import = "";
        for ($c = 0; $c < $num; $c++) {
            $line++;
            if ($row != 1) {
                //get data for import
                //if($line==1) { $import .= "$data[$c]<br /><br />"; }
                if ($line == 2) {
                    $import .= "$data[$c] $data2[district_id]<br />";
                    $data2['district_id'] = $data[$c];
                }
                if ($line == 3) {
                    $str = str_replace("nounit", "", $data[$c]);
                    $import .= "$str <br />";
                    $data2['troop_id'] = $str;
                }
                if ($line == 4) {
                    $import .= "$data[$c]<br />";
                    $data2['last_name'] = $data[$c];
                }
                if ($line == 5) {
                    $import .= "$data[$c]<br />";
                    $data2['first_name'] = $data[$c];
                }
                //if($line==6) { $import .= "$data[$c]<br /><br />"; }
                //if($line==7) { $import .= "$data[$c]<br /><br />"; }
                //if($line==8) { $import .= "$data[$c]<br /><br />"; }
                //if($line==9) { $import .= "$data[$c]<br /><br />"; }
                //if($line==10) { $import .= "$data[$c]<br /><br />"; }
                if ($line == 11) {
                    $import .= "$data[$c]<br />";
                    $data2['phone'] = $data[$c];
                }
                if ($line == 12) {
                    $import .= "$data[$c]<br />";
                    $data2['email'] = $data[$c];
                    if ($last_email == $data[$c]) {
                        $last_email = $data[$c];
                        $repeat = 1;
                    } else {
                        $last_email = $data[$c];
                        $repeat = 0;
                    }
                }
                //if($line==13) { $import .= "$data[$c]<br /><br />"; }
                if ($line == 13) {
                    $import .= "$data[$c]<br />";
                    $next_badge = $data[$c];
                }
                if ($line == 14) {
                    if ($data[$c] == "Any Scout from any unit") {
                        $import .= "YES<br />";
                        $data2['council'] = "YES";
                    } else {
                        $import .= "NO<br />";
                        $data2['council'] = "NO";
                    }
                }
                if ($line == 15) {
                    $date = $data[$c];
                    $ndate = date_create($date);
                    date_add($ndate, date_interval_create_from_date_string('2 years'));
                    $fdateString = date_format($ndate, 'Y-m-d');
                    $import .= "$data[$c] = $fdateString<br />";
                    $data2['ytp_exp'] = $fdateString;
                }
                //if($line==17) { $import .= "$data[$c]<br /><br />"; }
                //if($line==18) { $import .= "$data[$c]<br /><br />"; }
                // echo $data[$c] . "=$row $line<br />\n";

                //get badge id number
                $badge_id = $client_class->viewBadges_id($next_badge);
            }
        }
        if ($row != 1) {
            if ($repeat == 0) {
                //enter user and first badge
                echo "<hr><br />$import<br />$next_badge - $badge_id[badge_id]<br/>";

                $edit_done = $client_class->addClient($data2);
                echo $edit_done;
                if (isset($edit_done)) {
                    $current_id = $edit_done;
                }
                if (isset($edit_done) and $edit_done != "EXIST") {
                    $data_b = array();
                    $data_b['t_main_id'] = $current_id;
                    $data_b['t_badge_id'] = $badge_id['badge_id'];
                    $add_badge = $client_class->addBadges($data_b);

                }
                // Check to see if the user exisits.  If so, update their info.
                if (isset($edit_done) and $edit_done == "EXIST") {
                    $find_result = $client_class->findClient($data2);
                    if (isset($find_result)) {
                        $data2['list_id'] = $find_result['list_id'];
                        $client_class->editClient($data2);
                    }
                }
            }
            //enter extra badges
            else {echo "<br />$next_badge - $badge_id[badge_id] ";
                if (isset($edit_done) and $edit_done != "EXIST") {
                    $data_b = array();
                    $data_b['t_main_id'] = $current_id;
                    $data_b['t_badge_id'] = $badge_id['badge_id'];
                    $add_badge = $client_class->addBadges($data_b);
                }
            }
        }
    }
    fclose($handle);
}
