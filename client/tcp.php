<?php

# 测试连接TCP Swoole 服务器
$client = new \Swoole\Client(SWOOLE_SOCK_TCP);
$coonetBool = $client->connect('127.0.0.1', 9501);
if (!$coonetBool) {
    echo '连接失败';
    exit;
}

// php cli常量
fwrite(STDOUT, "请输入消息：");
$msg = trim(fgets(STDIN));

// 发送消息给 tcp server 服务器
$client->send($msg);

// 接受来自server的数据
$result = $client->recv();
echo $result;