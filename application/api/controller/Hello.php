<?php
/**
 * 文件路径： \application\index\job\Hello.php
 * 这是一个消费者类，用于处理 helloJobQueue 队列中的任务
 */
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
use think\queue\Job;
use think\Db;
class Hello {

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        $isJobDone = $this->send($data);
        if ($isJobDone) {
            //成功删除任务
            $job->delete();
        } else {
            //任务轮询4次后删除
            if ($job->attempts() > 3) {
                // 第1种处理方式：重新发布任务,该任务延迟10秒后再执行
                //$job->release(10);
                // 第2种处理方式：原任务的基础上1分钟执行一次并增加尝试次数
                //$job->failed();
                // 第3种处理方式：删除任务
                $job->delete();
            }
        }
    }

    /**
       * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
       */
  private function checkDatabaseToSeeIfJobNeedToBeDone($data){
    return true;
}

    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function send($data) {
        // 根据消息中的数据进行实际的业务处理...
        $result = Db::name('que')->insert([
            'time'=>$data['ts'],
            'status'=>$data['a']
        ]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
 
