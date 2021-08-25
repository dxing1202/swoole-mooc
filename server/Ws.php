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
        $this->server = new Swoole\WebSocket\Server($this->host, $this->port);

        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('close', [$this, 'onClose']);

        $this->server->start();
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

        // var_dump($request);
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
        $server->push($frame->fd, "Server-push:{$frame->data} Time:" . date('Y-m-d H:i:s'));
    }

    /**
     * onClose 监听WebSocket连接关闭事件
     * @param  object $server Swoole\WebSocket\Server 服务对象
     * @param  object $fd     客户端ID
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