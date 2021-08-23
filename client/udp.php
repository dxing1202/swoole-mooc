<?php

# 测试 TCP Swoole Client
# UDP客户端不需要连接服务，直接根据ip/端口发送
$client = new \Swoole\Client(SWOOLE_SOCK_UDP);

// php cli常量
fwrite(STDOUT, "请输入消息：");
$msg = trim(fgets(STDIN));

// 发送消息给 UDP server 服务器
$client->sendto('127.0.0.1', 9502, $msg);

// 接受来自server的数据
$result = $client->recv();
echo $result;

$client->close();