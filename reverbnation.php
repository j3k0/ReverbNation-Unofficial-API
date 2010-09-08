<?php
include "mycurl.php";

$LOGIN_URL = "http://www.reverbnation.com/user/login_from_dialog?message_region=become_fan_login_popup_message";
$STATUS_URL = "http://www.reverbnation.com/label/status_update_save/";

function rnInitLabel($labelID) {
    global $STATUS_URL;
    $STATUS_URL = "http://www.reverbnation.com/label/status_update_save/label_$labelID";
}
function rnInitArtist($artistID) {
    global $STATUS_URL, $ADD_SHOW_URL1, $ADD_SHOW_URL2, $ADD_SHOW_URL3, $ADD_SHOW_URL4;
    $STATUS_URL = "http://www.reverbnation.com/artist/status_update_save/artist_$artistID";
    $ADD_SHOW_URL1 = "http://www.reverbnation.com/show/add_show_from_artist_step_1_save/artist_$artistID";
    $ADD_SHOW_URL2 = "http://www.reverbnation.com/show/add_show_from_artist_step_2_private_save/artist_$artistID";
    $ADD_SHOW_URL3 = "http://www.reverbnation.com/show/add_show_from_artist_step_3_save/artist_$artistID";
    $ADD_SHOW_URL4 = "http://www.reverbnation.com/show/add_show_from_artist_step_4_save/artist_$artistID";
}

if (isset($_POST['init_label']))
    rnInitLabel($_POST['init_label']);
if (isset($_POST['init_artist']))
    rnInitArtist($_POST['init_artist']);

$action = $_GET['action'];
switch ($action) {

    case 'login':

        $data = http_build_query($_POST); 
        $ret = rnPost($LOGIN_URL, $data);
        if (strpos($ret, "Sign In Unsuccessful") !== false)
            echo "false:Wrong login or password.";
        else
            echo "true";
        break;

    case 'status':

        $data = http_build_query($_POST); 
        $ret = rnPost($STATUS_URL, $data);
        if (strpos($ret, "Must sign in or sign up to access") !== false) {
            echo "false:Must sign in.";
        }
        elseif (strpos($ret, "New Status") !== false) {
            echo "true";
        }
        else {
            echo "false:ReverbNation API is outdated";
            echo htmlspecialchars($ret);
        }
        break;

    case 'addshow':

        $venue = $_POST['venue'];
        $show_bill_artists = $_POST['show_bill_artists'];
        $date = $_POST['date'];

        $datex = explode(" ", $date);
        $showtime = $datex[0];
        $time = $datex[1];
        $timex = explode(":", $time);
        $hour = $timex[0];
        $minute = $timex[1];
        if ($hour == 0) {
            $ampm = 'AM';
            $hour = 12;
        }
        elseif ($hour >= 12) {
            if ($hour > 12)
                $hour -= 12;
            $ampm = 'PM';
        }
        else {
            $ampm = 'AM';
        }

        $add_show = array(
            "showtime" => $showtime,
            "private" => "true"
        );
        $date = array("hour" => $hour, "minute" => $minute, "ampm" => $ampm);
        $show_bill = array("ticket_details"=>"", "ticket_url"=>"", "details"=>"");

        $req1 = array("commit2"=>"", "add_show" => $add_show, "_" => "");
        $ret1 = rnPost($ADD_SHOW_URL1, http_build_query($req1));

        $req2 = array("commit2"=>"", "venue" => $venue, "_" => "");
        $ret2 = rnPost($ADD_SHOW_URL2, http_build_query($req2));

        $req3 = array("commit2"=>"", "show_bill_artists" => $show_bill_artists, "date" => $date, "show_bill"=>$show_bill, "_" => "");
        $ret3 = rnPost($ADD_SHOW_URL3, http_build_query($req3));

        $req4 = array("commit2"=>"", "_" => "");
        $ret4 = rnPost($ADD_SHOW_URL4, http_build_query($req4));

        echo "true"; // TODO: Error handling...
        break;

    default:
        echo "ERROR";

}
