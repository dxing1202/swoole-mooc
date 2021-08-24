<?php

//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

// 设置 ws server 配置
// $ws->set([]);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    // Log记录 连接来访
    $serverInfo = $request->server;
    $date = date('Y-m-d H:i:s', $serverInfo["request_time"]);
    $log = sprintf("Client:%s Time:%s IP:%s Connection successful\n", $request->fd, $date, $serverInfo['remote_addr']);
    echo $log;

    // var_dump($request);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    // 当前时间
    $date = date('Y-m-d H:i:s');
    $log = sprintf("Client:%s Time:%s Disconnected\n", $fd, $date);
    echo $log;
});

$ws->start();
