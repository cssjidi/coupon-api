<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<div style="width:750px;">
<?php
$str = "'http:' ? '//dsc.taobaocdn.com/i6/440/731/44173983271/TB1ileNo22H8KJjy1zk8qtr7pla.desc%7Cvar%5Edesc%3Bsign%5Ec4699dac9f17867de260e27cd5d6ffa2%3Blang%5Egbk%3Bt%5E1519788934'";
preg_match_all('/(dsc\.taobaocdn\.com.*?)[\'|\"]/si',$str,$match);
var_dump($match);
?>
</div>
</body>
</html>
