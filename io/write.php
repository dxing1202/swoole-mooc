<?php

# 采用 Hook 原生 PHP 函数的方式实现协程客户端
# 通过一行代码就可以让原来的同步 IO 的代码变成可以协程调度的异步 IO，即一键协程化

$filePath = __DIR__ . "/1.log";

# 设置加载协程Hook 
Co::set(['hook_flags' => SWOOLE_HOOK_FILE]);

Co\run(function () use ($filePath) {
    
    $fp = fopen($filePath, "w");

    var_dump($fp);

    // $content = date("Ymd H:i:s");

   	// $fwResult = fwrite($fp, $content);

   	// if ( $fwResult ) {
   	// 	echo "success" . PHP_EOL;
   	// }

   	// fclose($fp)

});

// 测试顺序运行
echo "start" . PHP_EOL;