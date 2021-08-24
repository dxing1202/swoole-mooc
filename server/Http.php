<?php

# 技巧点
# netstat -anp | grep 8080
# 如果端口被占用，可以用以上的命令进行查询 是哪些程序|服务占用，Kill掉

$http = new Swoole\Http\Server('0.0.0.0', 9501);

// 
$http->

$http->on('request', function ($request, $response) {
    // 解决Chrome 请求两次问题
    // if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
    //     $response->end();
    //     return;
    // }

    // URL路由
    // list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
    //根据 $controller, $action 映射到不同的控制器类和方法
    // (new $controller)->$action($request, $response);

    // 设置header头部信息
    $response->header('Content-Type', 'text/html; charset=utf-8');
    // 打印GET请求
    // var_dump($request->get);
    // 设置cookie
    // $response->cookie("singwa", "xsss", time() + 1800);
    $response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . "<br/>GET: " . json_encode($request->get) . '</h1>');
});

$http->start();
enable_static_handler
# 测试端口
# curl 127.0.0.1:8080