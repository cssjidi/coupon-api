<?php
$time=5;
$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$count = 0;
getData($count);
sleep($time);
file_get_contents($url);

function getData($count){
    $count++;
    echo $count;
}
?>