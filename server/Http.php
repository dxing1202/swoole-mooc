<?php

# 技巧点
# netstat -anp | grep 8080
# 如果端口被占用，可以用以上的命令进行查询 是哪些程序|服务占用，Kill掉

$http = new Swoole\Http\Server('0.0.0.0', 9501);

$http->on('request', function ($request, $response) {
    $response->header('Content-Type', 'text/html; charset=utf-8');
    // 打印GET请求
    // var_dump($request->get);
    $response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . 'GET: ' . json_encode($request->get) . '</h1>');
});

$http->start();

# 测试端口
# curl 127.0.0.1:8080