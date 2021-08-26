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
    protected $serv = null;
    // 监听主机 对应外网的IP 0.0.0.0监听所有ip
    protected $host = '0.0.0.0';
    // 监听端口号 
    protected $port = 9502;

    public function __construct()
    {
        //创建WebSocket Server对象
        $this->serv = new Swoole\WebSocket\Server($this->host, $this->port);
        // 服务配置
        // $this->serv->set([]);

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
     * @param  object $serv Swoole\WebSocket\Serve 服务对象
     * @param  object $request Swoole\Http\Request 请求对象信息
     * @return void   无
     */
    public function onOpen($serv, $request)
    {
        # Log记录 连接连接
        // 请求时间
        $date = date('Y-m-d H:i:s', $request->serv["request_time"]);
        $log = sprintf("Time:%s Client:%s IP:%s Connection successful\n", $date, $request->fd, $request->server['remote_addr']);
        echo $log;

        // var_dump($request);
        $server->push($request->fd, "hello, welcome\n");
    }

    /**
     * onMessage 监听WS消息事件
     * @param  object $serv Swoole\WebSocket\Serve 服务对象
     * @param  object $frame  Swoole\WebSocket\Frame 客户端发来的数据帧信息对象
     * @return void   无
     */
    public function onMessage($serv, $frame) {
        echo "Message: {$frame->data}\n";
        // Todo 10s
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        $serv->task($data);
        $serv->push($frame->fd, "Server-push:{$frame->data} Time:" . date('Y-m-d H:i:s'));
    }

    /**
     * onTask 异步任务回调函数
     * @param  object $serv Swoole\WebSocket\Serve 服务对象
     * @param  int $task_id    [description]
     * @param  int $reactor_id [description]
     * @param  array $data     [description]
     * @return void 无
     */
    public function onTask($serv, $task_id, $reactor_id, $data)
    {
        echo "New AsyncTask[id={$task_id}]".PHP_EOL;
        // 返回任务执行的结果
        // $this->serv->finish("{$data} -> OK");
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "AsyncTask[{$task_id}] Finish: {$data}".PHP_EOL;
    }

    /**
     * onClose 监听WebSocket连接关闭事件
     * @param  object $serv Swoole\WebSocket\Server 服务对象
     * @param  object $fd   客户端ID
     * @return void   无
     */
    public function onClose($serv, $fd)
    {
        # Log记录 关闭事件
        // 当前时间
        $date = date('Y-m-d H:i:s');
        $log = sprintf("Time:%s Client:%s Disconnected\n", $date, $fd);
        echo $log;
    }
}

$ws = new Ws(); 