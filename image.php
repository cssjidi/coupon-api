<?php
    //过滤所有的img
    $url = "https://www.mmkao.cc/";
    $str = file_get_contents($url);
    $preg = '/<img[^>]*\/>/';
    preg_match_all($preg, $str, $matches);
    var_dump($matches);
    $matches = $matches[0];

    //获取src中的链接
    $arr = [];
    foreach($matches as $v){
        $preg = '/http:\/\/.*.jpg/';
        preg_match_all($preg, $v, $match);
        var_dump($match);
        //$arr[] = $match[0][0];
    }
    //文件保存地址
    $dir = 'E:/abs/img/';

    foreach($arr as $k => $v){
        //图片名称
        $name = $dir . $k . '.jpg';
        //下载
        //download($name, $v);
    }
    function download($name, $url){
        if(!is_dir(dirname($name))){
            mkdir(dirname($name));
        }
        $str = file_get_contents($url);
        file_put_contents($name, $str);
        //输出一些东西,要不窗口一直黑着,感觉怪怪的
        echo strlen($str);
        echo "\n";
    }

