<?php
namespace app\api\controller;

use Redis;
header("Content-Type: text/html;charset=utf-8");
class Index
{
    public function index()
    {
        $redis = new Redis();

        $redis->connect('127.0.0.1',6379);
        $arr = array('{dda:qq,aa:22}',['aa','bb']);
        foreach($arr as $k=>$v){
            $redis->rpush("myqueue",$v);
            echo $k."号入队成功"."<br/>";
            /*
             *  0号入队成功
             *  1号入队成功
             *  2号入队成功
             *  3号入队成功
             *  4号入队成功
             *  5号入队成功
             */

        }

    }



}

