<?php
/**
 * 案例
 * 获取多个URL数据
 * 
 * 场景比较符合： 可以分散执行的代码
 */
 
use Swoole\Process;
use Swoole\Coroutine;
use Swoole\Coroutine\System;
 
$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://baidu.com?search=singwa',
    'http://baidu.com?search=singwa2',
    'http://baidu.com?search=singwa3',
    'http://baidu.com?search=imooc',
];

// 传统做法
// foreach ($urls as $url) {
//     $content[] = file_get_contents($url);
// }

// 输出运行开始时间
echo "process-start-time：" . date("Y-m-d H:i:s") . PHP_EOL;
$workers = [];

for($i = 0; $i < count($urls); $i++) {
    // 子进程
    $process = new Process(function(Process $worker) use ($i, $urls) {
        // todo curl
        $content = curlData($urls[$i]);
        // echo $content . PHP_EOL;
        // echo 等于 write
        $worker->write( $content . PHP_EOL );
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

// foreach ($workers as $process) {
//     // 输出进程管道内容
//     echo $process->read();
// }

// 根据上面的改进
// 自定义添加上回收机制
// 根据Swoole文档最新推荐 使用此方案进行回收
Coroutine\run(function () use ($workers) {
    foreach ($workers as $process) {
        // 输出进程管道内容echo
        echo $process->read();
        
        $status = System::wait();
        assert($status['pid'] === $process->pid);
        var_dump($status);
    }
});

// 输出运行结束时间
echo "process-end-time：" . date("Y-m-d H:i:s") . PHP_EOL;

/**
 * 模拟请求URL的内容 1s
 * @param $url string
 * @param $i int 睡眠时间 自定义加上的测试运行时间
 * @return string
 */
function curlData($url, $i = 1) {
    // curl file_get_contents
    sleep($i);
    return $url . " success" . PHP_EOL; 
}