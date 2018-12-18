<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
use Redis;
class Oqueue
{
    public function index()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        $value = $redis->lpop('myqueue');
        if($value){
            echo "出队的值".$value;
        }else{
            echo "出队完成";

        }
    }
}
