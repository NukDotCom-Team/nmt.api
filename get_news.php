<?php
require "simple_html_dom.php";
function replace_substr($str, $what, $with){
    $i = 1;
    while (($offset = strpos($str, $what)) !== false) {
        $str = substr_replace($str, $with, $offset, strlen($what));
    }
    return $str;
}
function escapeJsonString($value) {
    # list from www.json.org: (\b backspace, \f formfeed)
    $escapers =     array("\r");
    $replacements = array("");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}
$json=array();
$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];
$output = file_get_contents("http://www.nmt.edu.ru/index.php?limit=".$limit."&start=".$start);
$html = str_get_html($output);
$name = $html->find("div.componentheading", 0);
$name = $name->innertext();
$json['status'] = "200";
$json['name'] = $name;
$news = $html->find("div.itemList",0);
$news = str_get_html($news->innertext());
$items = $news->find("div.itemContainer");
$itemsArray = array();
$html->clear();
$i = 0;
foreach ($items as $item){
    $itemCode = str_get_html($item->innertext());
    $header = $itemCode->find("h3.catItemTitle",0);
    $title = replace_substr($header->plaintext,'"', "");
    $itemArray = [
        'title' => $title,
        'link' => $itemCode->find("a.k2ReadMore", 0)->href,
        'body' => $itemCode->find("div.catItemIntroText",0)->innertext()
    ];
    echo $i+1 . ". ".$title."<br>";
    $itemsArray[$i] = $itemArray;
    $i++;
}
$json['items'] = $itemsArray;
$news->clear();
echo replace_substr(json_encode($json, JSON_UNESCAPED_UNICODE), "\\t", "");
//This Code is not working. Please wait until it will be continued.