<?php

//Swoole\Server对象
$serv = null;
//监听ip 对应外网的IP 0.0.0.0监听所有ip
$host = '127.0.0.1';
//监听端口号
$port = 9502;

$serv = new \Swoole\Server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
$serv->set([
    //设置启动的worker进程数。【默认值：CPU 核数】
    'worker_num' => 2,
    //设置每个worker进程的最大任务数。【默认值：0 即不会退出进程】
    'max_request' => 10000,
    //开启守护进程化【默认值：0】
    'daemonize' => 0,
]);

//监听数据接收事件
// 3、设置回调，发生在worker进程中
$serv->on('packet', function ($serv, $data, $client_info) {
    echo "接收到客户端信息: " . $data . PHP_EOL;
    var_dump($client_info);
    // 由于UDP协议不能确保信息送达，所以当服务端收到信息后最好做个应答，这样客户端才有依据来做判断
    $serv->sendto($client_info['address'], $client_info['port'], "This is server..." . PHP_EOL);
});

//启动服务器
$serv->start();