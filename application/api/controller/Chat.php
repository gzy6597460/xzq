<?php
namespace  app\api\controller;

use think\Controller;
use think\Db;
use think\Request;


class Chat extends Controller {
    /**
     * 文本消息数据持久化
     */
    public function save_message()
    {
        if (Request::instance()->isAjax()){
            $message = input("post.");

            $datas['fromid']=$message['fromid'];
            $datas['fromname']= $this->getName($message['fromid']);
            $datas['toid']=$message['toid'];
            $datas['toname']=$this->getName($message['toid']);
            $datas['content']=$message['data'];
            $datas['time']=$message['time'];
            $datas['isread']=$message['isread'];
            $datas['type']=1;

            Db::name('communication')->insert($datas);
        }
    }

    /**
     * 根据用户ID返回昵称
    */
    public function getName($uid){
        $username =\db('user')->where('id',$uid)->field('nickname')->find();
        return $username['nickname'];
    }

    /**
     * 根据用户ID返回头像
     */
    public function get_head(){
        if (Request::instance()->isAjax()){
            $fromid = input("fromid");
            $toid = input("toid");
            $frominfo =\db('user')->where('id',$fromid)->field('headimgurl,nickname')->find();
            $toinfo =\db('user')->where('id',$toid)->field('headimgurl,nickname')->find();

            return [
                'from_head'=>$frominfo['headimgurl'],
                'from_name'=>$frominfo['nickname'],
                'to_head'=>$toinfo['headimgurl'],
                'to_name'=>$toinfo['nickname'],
            ];
        }
    }

    /**
     * 页面加载返回聊天记录
     */
    public function load(){
        if(Request::instance()->isAjax()){
            $fromid = input('fromid');
            $toid = input('toid');
            $count =  Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->count('id');
            if($count>=10){
                $message = Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->limit($count-10,10)->order('id')->select();
            }else{
                $message = Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->order('id')->select();
            }
            return $message;
        }
    }
    /**
     * @param $fromid
     * @param $toid
     * 根据fromid来获取fromid同toid发送的未读消息。
     */
    public function getCountNoread($fromid,$toid){

        return Db::name('communication')->where(['fromid'=>$fromid,'toid'=>$toid,'isread'=>0])->count('id');

    }
    /**
     * @param $uid
     * 根据uid来获取它的头像
     */
    public function get_head_one($uid){

        $fromhead = Db::name('user')->where('id',$uid)->field('headimgurl')->find();

        return $fromhead['headimgurl'];
    }

    /**
     * @param $fromid
     * @param $toid
     * 根据fromid和toid来获取他们聊天的最后一条数据
     */
    public function getLastMessage($fromid,$toid){

        $info = Db::name('communication')->where('(fromid=:fromid&&toid=:toid)||(fromid=:fromid2&&toid=:toid2)',['fromid'=>$fromid,'toid'=>$toid,'fromid2'=>$toid,'toid2'=>$fromid])->order('id DESC')->limit(1)->find();

        return $info;
    }

    /**
     * 根据fromid来获取当前用户聊天列表
     */
    public function get_list()
    {
        if (Request::instance()->isAjax()) {
            $fromid = input('id');
            $info = Db::name('communication')->field(['fromid', 'toid', 'fromname'])->where('toid', $fromid)->group('fromid')->select();

            $rows = array_map(function ($res) {
                return [
                    'head_url' => $this->get_head_one($res['fromid']),
                    'username' => $res['fromname'],
                    'countNoread' => $this->getCountNoread($res['fromid'], $res['toid']),
                    'last_message' => $this->getLastMessage($res['fromid'], $res['toid']),
                    'chat_page' => "http://chat.com/index.php/index/index/index?fromid={$res['toid']}&toid={$res['fromid']}"
                ];

            }, $info);

            return $rows;
        }
    }

    public function changeNoRead(){
        if (Request::instance()->isAjax()){
            $fromid= input('toid');
            $toid= input('fromid');
            Db::name('communication')->where(['fromid'=>$fromid,'toid'=>$toid,])->update('isread',1);
        }
    }
}