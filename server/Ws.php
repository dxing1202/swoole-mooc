<?php

/**
 * WebSocket Swoole Server Class
 * @author dxing1202
 * @copyright dxing1202.cn
 * @since 2021-08-25
 */
class Ws
{
    // Swoole\Server对象
    protected $server = null;
    // 监听主机 对应外网的IP 0.0.0.0监听所有ip
    protected $host = '0.0.0.0';
    // 监听端口号 
    protected $port = 9502;

    public function __construct()
    {
        //创建WebSocket Server对象
        $this->serv = new Swoole\WebSocket\Server($this->host, $this->port);
        // 服务配置
        $this->serv->set([
            'task_worker_num' => 2
        ]);

        $this->serv->on('open', [$this, 'onOpen']);
        $this->serv->on('message', [$this, 'onMessage']);

        // 执行异步任务回调函数
        $this->serv->on('task', [$this, 'onTask']);
        $this->serv->on('finish', [$this, 'onFinish']);
        
        $this->serv->on('close', [$this, 'onClose']);

        $this->serv->start();
    }

    /**
     * onOpen 监听WS连接事件
     * @param  object $server Swoole\WebSocket\Serve 服务对象
     * @param  object $request Swoole\Http\Request 请求对象信息
     * @return void   无
     */
    public function onOpen($server, $request)
    {
        # Log记录 连接连接
        // 请求时间
        $date = date('Y-m-d H:i:s', $request->server["request_time"]);
        $log = sprintf("Time:%s Client:%s IP:%s Connection successful\n", $date, $request->fd, $request->server['remote_addr']);
        echo $log;
        
        // 测试Timer
        if ( $request->fd == 1 ) {
            // 每2秒执行
            swoole_timer_tick(2000, function($time_id) {
                echo "2s: timerId: {$time_id}" . PHP_EOL;
            });
        }
        $server->push($request->fd, "hello, welcome\n");
    }

    /**
     * onMessage 监听WS消息事件
     * @param  object $server Swoole\WebSocket\Serve 服务对象
     * @param  object $frame  Swoole\WebSocket\Frame 客户端发来的数据帧信息对象
     * @return void   无
     */
    public function onMessage($server, $frame) {
        echo "Message: {$frame->data}\n";
        // Todo 10s
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        // 异步任务
        // $server->task($data);
        
        swoole_timer_after(5000, function() use($server, $frame) {
            echo "5s-after\n";
            $server->push($frame->fd, "server-time-after:");
        });
        // swoole_timer_tick(500, function() use($server, $frame) {
        //     $server->push( $frame->fd, time() );
        // });
        $server->push($frame->fd, "Server-push:{$frame->data} Time:" . date('Y-m-d H:i:s'));
    }

    /**
     * onTask 异步任务回调函数
     * @param  object $server Swoole\WebSocket\Serve 服务对象
     * @param  int $task_id    任务ID 由Swoole扩展内自动生成，用于区分不同的任务
     * @param  int $reactor_id 来自于哪个worker进程
     * $task_id和$reactor_id组合起来才是全局唯一的，不同的wokrer进程投递的任务ID可能会有相同
     * @param  array $data     任务内容&数据
     * @return void 无
     */
    public function onTask($server, $task_id, $reactor_id, $data)
    {
        // print_r($data);
        echo "New AsyncTask[id={$task_id}]".PHP_EOL;
        // 耗时场景
        sleep(10);
        return "on task finish"; // 返回 告诉worker
        
        // 任务完成自动执行 finish 回调函数
    }

    public function onFinish($server, $task_id, $data)
    {
        echo "AsyncTask[{$task_id}] Finish: {$data}".PHP_EOL;
    }

    /**
     * onClose 监听WebSocket连接关闭事件
     * @param  object $server Swoole\WebSocket\Server 服务对象
     * @param  object $fd   客户端ID
     * @return void   无
     */
    public function onClose($server, $fd)
    {
        # Log记录 关闭事件
        // 当前时间
        $date = date('Y-m-d H:i:s');
        $log = sprintf("Time:%s Client:%s Disconnected\n", $date, $fd);
        echo $log;
    }
}

$ws = new Ws(); 