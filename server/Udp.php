<?php
namespace server;

/**
 * Swoole UDP Server
 * @author DXing1202
 */
class Udp
{
    //Swoole\Server对象
    protected $serv = null;
    //监听ip 对应外网的IP 0.0.0.0监听所有ip
    protected $host = '127.0.0.1';
    //监听端口号
    protected $port = 9502;

    public function __construct()
    {

      $this->serv = new \Swoole\Server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

        //设置参数
        //如果业务代码是全异步 IO 的，worker_num设置为 CPU 核数的 1-4 倍最合理
        //如果业务代码为同步 IO，worker_num需要根据请求响应时间和系统负载来调整，例如：100-500
        //假设每个进程占用 40M 内存，100 个进程就需要占用 4G 内存
        $this->serv->set(array(
            //设置启动的worker进程数。【默认值：CPU 核数】
            'worker_num' => 4,
            //设置每个worker进程的最大任务数。【默认值：0 即不会退出进程】
            'max_request' => 10000,
            //开启守护进程化【默认值：0】
            'daemonize' => 0,
        ));

        //监听数据接收事件
        // 3、设置回调，发生在worker进程中
        $this->serv->on('packet', function ($serv, $data, $client_info) {
            echo "接收到客户端信息: " . $data . PHP_EOL;
            var_dump($client_info);
            // 由于UDP协议不能确保信息送达，所以当服务端收到信息后最好做个应答，这样客户端才有依据来做判断
            $this->serv->sendto($client_info['address'], $client_info['port'], "This is server..." . PHP_EOL);
        });
    }
}

$udp = new Udp();