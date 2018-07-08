<?php
function getData($url,$data,$cookie=null)
{
    //初始化
    $curl = curl_init();
//设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
//设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
//设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }

//设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//执行命令
    $result = curl_exec($curl);
//关闭URL请求
    curl_close($curl);
//显示获得的数据
    return $result;
}

$uri = 'http://localhost:9090/admin/index.php?route=common/login';
$post_data = array(
    'username'=>'admin',
    'password'=>'admin'
);

$str = getData($uri,$post_data);
$match = explode('=',$str);
$session =  preg_replace('/;.*/','',$match[1]);
$token = preg_replace('/\s.*/','',$match[4]);
//union
$union_url = 'http://localhost:9090/admin/index.php?route=catalog/union&user_token='.$token;
$union_data = array(
    'page' => '1',
    'limit' => '100'
);
getData($union_url,$union_data,'OCSESSID='.$session);
