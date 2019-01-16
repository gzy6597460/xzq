<?php
namespace app\api\controller;

use GuzzleHttp\Client;
use think\Request;
header("Content-Type: text/html;charset=utf-8");
class Test{

    public function test(Request $request){
//        $data = db('user')->select();
//        $res = $this->array_unset_tt($data,'passw');
//        var_dump($res);
//        array_unique($data);
//        $res = array_count_values($data);
//        var_dump($res);
//        $res = getcwd();
//        $s = file_get_contents('http://www.baidu.com');


//        $client = new Client();
//        $res = $client->request('GET', 'http://www.baidu.com/');
//        echo $res->getStatusCode();
//        $body = $res->getBody()->getContents(); //获取响应体，对象
//        $bodyStr = (string)$body; //对象转字串,这就是请求返回的结果
//        echo $bodyStr;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://www.baidu.com/');
        echo $res->getStatusCode();
        echo $res->getBody();
    }

    function array_unset_tt($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                unset($value[$key]);  //有：销毁
            }else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }

}