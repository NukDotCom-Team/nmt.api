<?php
error_reporting(0);
function replace_substr($str, $what, $with){
    $i = 1;
    while (($offset = strpos($str, $what)) !== false) {
        $str = substr_replace($str, $with, $offset, strlen($what));
    }
    return $str;
}
require "simple_html_dom.php";
$days = $_REQUEST['days'];
if(group !== "") {
    $group = urldecode($_REQUEST['group']);
}
$output = file_get_contents("http://www.nmt.edu.ru/index.php?option=com_schedule&view=schedule&days=".$days);
$html = str_get_html($output);
$schedule_name = $html->find('div[style=\'font-size: 18px; padding-bootom: 5px;\']', 0)->innertext();
$change_name = $html->find('div[style=\'font-size: 18px; padding-bootom: 5px;\']', 1)->innertext();
$schedule = $html->find('table.schedule',0);
$schedule = str_get_html($schedule->innertext());
$html->clear();
$paras = $schedule->find('th.schedule, th.end_cell');
$auditories_work = $schedule->find('td.first_cell');
$auditories = array();
$i = 0;
if($group == "") {
    foreach ($auditories_work as $auditory){
        $auditories[$i] = $auditory->innertext();
        $i++;
    }
    $sessions_work = $schedule->find('td.schedule');
    $i = 0;
    $sessions = array();
    foreach ($sessions_work as $session) {
        $session_processed = replace_substr($session->innertext(), "&nbsp;", "");
        $session_processed = replace_substr($session_processed, "&nbnbsp;", "");
        $session_processed = replace_substr($session_processed, "&nnbsp;", "");
        $sessions[$i] = $session_processed;
        $i++;
    }
    $sessions_grouped = array();
    $h = 0;
    for ($l = 0; $l <= count($auditories)-1; $l++){
        $sessions_grouped_work = array();
        $sessions_grouped_work['auditory'] = $auditories[$l];
        $sessions8 = array();
        for ($j = 0; $j <= 7; $j++){
            $sessions8[$j] = $sessions[$h];
            $h++;
        }
        $sessions_grouped_work['sessions'] = $sessions8;
        $sessions_grouped[$l] = $sessions_grouped_work;
    }
    $sessions_grouped_work = null;
    $sessions8 = null;
    $sessions = null;
    $json = array();
    $json['status'] = "200";
    $json['days'] = $days;
    $json['schedule_name'] = $schedule_name;
    $json['sessions'] = $sessions_grouped;
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
else{
    foreach ($auditories_work as $auditory){
        $auditories[$i] = $auditory->innertext();
        $i++;
    }
    $sessions_work = $schedule->find('td.schedule');
    $i = 0;
    $sessions = array();
    foreach ($sessions_work as $session) {
        $session_processed = replace_substr($session->innertext(), "&nbsp;", "");
        $session_processed = replace_substr($session_processed, "&nbnbsp;", "");
        $session_processed = replace_substr($session_processed, "&nnbsp;", "");
        $sessions[$i] = $session_processed;
        $i++;
    }
    $sessions_grouped = array();
    $h = 0;
    for ($l = 0; $l <= count($auditories)-1; $l++){
        $sessions_grouped_work = array();
        $sessions_grouped_work['auditory'] = $auditories[$l];
        $sessions8 = array();
        for ($j = 0; $j <= 7; $j++){
            $sessions8[$j] = $sessions[$h];
            $h++;
        }
        $sessions_grouped_work['sessions'] = $sessions8;
        $sessions_grouped[$l] = $sessions_grouped_work;
    }
    $sessions_grouped_work = null;
    $sessions8 = null;
    $sessions = null;
    $i = 0;
    $h = 0;
    $sessions_out = array();
    foreach ($sessions_grouped as $item){
        for($j = 0; $j<=7; $j++){
            if (explode(" ", $item['sessions'][$j])[0] == $group){
                $sessions_out_work = array();
                $sessions_triplete = array();
                $sessions_triplete['number'] = $j+1;
                $sessions_triplete['session'] = $item['sessions'][$j];
                $sessions_triplete['auditory'] = $item['auditory'];
                $sessions_out_work['record'] = $sessions_triplete;
                $sessions_out[$h] = $sessions_out_work;
                $h++;
            }
        }
        $i++;
    }
    $json = array();
    $json['status'] = "200";
    $json['days'] = $days;
    $json['schedule_name'] = $schedule_name;
    $json['records'] = $sessions_out;
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
