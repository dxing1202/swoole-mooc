<?php

use Swoole\Process;
use Swoole\Coroutine;
use Swoole\Coroutine\System;

/**
 * 备注 技巧
 * 
 * 查询端口
 * netstat -anp | grep 9501
 * 
 * 查询当前文件(获取此文件运行进程的Pid) 进程之间的关系
 * ps aux | grep process.php
 * 
 * 查询进程关系图 参数是( 此文件运行进程的Pid )
 * pstree -p 13667
 * 
 * 查询子进程开启的http服务进程 ( 对应Pid查看关系图 )
 * ps aft | grep Http
 */

$process = new Process(function(\Swoole\Process $worker) {
    // todo
    // echo 111 . PHP_EOL;
    // 执行一个外部程序，此函数是 exec 系统调用的封装。
    $worker->exec("/www/server/php/80/bin/php", [__DIR__ . '/../server/Http.php']);
}, false);

$pid = $process->start();

echo '主进程PID：' . getmygid() . PHP_EOL;
echo $pid . PHP_EOL;

// 根据Swoole文档最新推荐 使用此方案进行回收
Coroutine\run(function () use ($process) {
    $status = System::wait();
    // assert($status['pid'] === $process->pid);
    var_dump($status);
});

// for ($n = 1; $n <= 3; $n++) {
//     $process = new Process(function () use ($n) {
//         echo 'Child #' . getmypid() . " start and sleep {$n}s" . PHP_EOL;
//         sleep($n);
//         echo 'Child #' . getmypid() . ' exit' . PHP_EOL;
//     });
//     $process->start();
// }
// for ($n = 3; $n--;) {
//     $status = Process::wait(true);
//     echo "Recycled #{$status['pid']}, code={$status['code']}, signal={$status['signal']}" . PHP_EOL;
// }
// echo 'Parent #' . getmypid() . ' exit' . PHP_EOL;