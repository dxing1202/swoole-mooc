<?php

# 技巧点
# Linux运行 可以查看进程数 tcp.php 为实际运行文件
// ps aft | grep tcp.php


//创建Server对象，监听 127.0.0.1:9501 端口
$server = new Swoole\Server('127.0.0.1', 9501);

$server->set([
	// worker进程数 建议： 1-4倍CPU核数 【默认值：CPU 核数】
	'worker_num'  => 2,
	// 设置 worker 进程的最大任务数。【默认值：0 即不会退出进程】
	'max_request' => 10000
]);

//监听连接进入事件
// $fd 客户端连接的唯一标识 连接的文件描述符
// $reactor_id 连接所在的 Reactor 线程 ID
$server->on('Connect', function ($server, $fd, $reactor_id) {
    echo "Client: {$reactor_id} - {$fd} - Connect.\n";
});

//监听数据接收事件
$server->on('Receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Server: {$reactor_id} - {$fd} - {$data}");
});

//监听连接关闭事件
$server->on('Close', function ($server, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$server->start(); 
