<?php

# 技巧点
# Linux运行 可以查看进程数 tcp.php 为实际运行文件
// ps aft | grep tcp.php

/**
 * TCP Swoole Server Class
 * @author dxing1202
 * @copyright dxing1202.cn
 * @since 2021-08-26
 */
class Tcp
{
	// Swoole\Server对象
    protected $server = null;
    // 监听主机 对应外网的IP 0.0.0.0监听所有ip
    protected $host = '127.0.0.1';
    // 监听端口号 
    protected $port = 9501;

    public function __construct()
    {
        //创建TCP Server对象
        $this->server = new Swoole\Server($this->host, $this->port);

        $this->server->set([
			// worker进程数 建议： 1-4倍CPU核数 【默认值：CPU 核数】
			'worker_num'  => 2,
			// 设置 worker 进程的最大任务数。【默认值：0 即不会退出进程】
			'max_request' => 10000
		]);

        $this->server->on('open', [$this, 'onConnect']);
        $this->server->on('receive', [$this, 'onReceive']);
        $this->server->on('close', [$this, 'onClose']);

        # 启动服务
        $this->server->start();
    }

    //监听连接进入事件
	// $fd 客户端连接的唯一标识 连接的文件描述符
	// $reactor_id 连接所在的 Reactor 线程 ID
    public function onConnect($server, $fd, $reactor_id)
    {
    	echo "Client: {$reactor_id} - {$fd} - Connect.\n";
    }

    //监听数据接收事件
    public function onReceive($server, $fd, $reactor_id, $data)
    {
    	$server->send($fd, "Server: {$reactor_id} - {$fd} - {$data}");
    }

    //监听连接关闭事件
    public function onClose($server, $fd)
    {
    	echo "Client: Close.\n";
    }

}
