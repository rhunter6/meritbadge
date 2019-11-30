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

$data2 = array();
if (($handle = fopen("./inc/Merit.csv", "r")) !== false) {
    // Skip the first line
    $data = fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $num = count($data);
        // Which column are we processing.
        $line = 0;
        $import = "";
        for ($c = 0; $c < $num; $c++) {
            $line++;
            //get data for import
            // First is BSA ID number
            //if($line==1) { $import .= "$data[$c]<br /><br />"; }
            if ($line == 2) {
                $import .= "$data[$c]<br />";
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
            if ($line == 6) {
                $import .= "$data[$c]<br />";
                $data2['phone'] = $data[$c];
            }
            if ($line == 7) {
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
            if ($line == 8) {
                $import .= "$data[$c]<br />";
                $next_badge = $data[$c];
            }
            if ($line == 9) {
                if ($data[$c] == "Any Scout from any unit") {
                    $import .= "YES<br />";
                    $data2['council'] = "YES";
                } else {
                    $import .= "NO<br />";
                    $data2['council'] = "NO";
                }
            }
            if ($line == 10) {
                $date = $data[$c];
                $ndate = date_create($date);
                date_add($ndate, date_interval_create_from_date_string('2 years'));
                $fdateString = date_format($ndate, 'Y-m-d');
                $import .= "$data[$c] = $fdateString<br />";
                $data2['ytp_exp'] = $fdateString;
            }

            //get badge id number
            $badge_id = $client_class->viewBadges_id($next_badge);
        }
        if ($repeat == 0) {
            // Save user and first badge
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
        // Save extra badges
        else {
            echo "<br />$next_badge - $badge_id[badge_id] ";
            if (isset($edit_done) and $edit_done != "EXIST") {
                $data_b = array();
                $data_b['t_main_id'] = $current_id;
                $data_b['t_badge_id'] = $badge_id['badge_id'];
                $add_badge = $client_class->addBadges($data_b);
            }
        }
    }
    fclose($handle);
}
