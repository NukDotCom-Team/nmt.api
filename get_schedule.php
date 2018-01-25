<?php
function replace_substr($str, $what, $with){
    $i = 1;
    while (($offset = strpos($str, $what)) !== false) {
        $str = substr_replace($str, $with, $offset, strlen($what));
    }
    return $str;
}
require "simple_html_dom.php";
$days = $_REQUEST['days'];
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
foreach ($auditories_work as $auditory){
    $auditories[$i] = $auditory->innertext();
    $i++;
}
$sessions_work = $schedule->find('td.schedule');
$i = 0;
$sessions = array();
foreach ($sessions_work as $session){
    $session_processed = replace_substr($session->innertext(), "&nbsp;", "");
    $session_processed = replace_substr($session_processed, "&nbnbsp;", "");
    $session_processed = replace_substr($session_processed, "&nnbsp;", "");
    $sessions[$i] = $session_processed;
    $i++;
}
$json = array();
$json['status'] = "200";
$json['days'] = $days;
$json['schedule_name'] = $schedule_name;
$json['change_name'] = $change_name;
$json['auditories'] = $auditories;
$json['sessions'] = $sessions;
echo json_encode($json, JSON_UNESCAPED_UNICODE);