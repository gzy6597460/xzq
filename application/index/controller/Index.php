<?php
namespace app\index\controller;

use think\Controller;
use think\Cache;

class Index extends  Controller
{
    public function index()
    {
        $fromid = input("fromid");
        $toid = input('toid');
        $this->assign('fromid',$fromid);
        $this->assign('toid',$toid);
        return $this->fetch();
    }

    public function lists(){

        $this->assign('fromid',input('fromid'));
        return $this->fetch();
    }

    public function test(){
//        $options = [
//            // 缓存类型为File
//            'type'   => 'redis',
//            // 服务器地址
//            'host'       => '127.0.0.1',
//        ];
//        Cache('guozeying','13225938894',$options);
        Cache::store('redis')->set('guozeying','13g');
        $value = Cache::store('redis')->get('guozeying');
        dump($value);
    }
}
