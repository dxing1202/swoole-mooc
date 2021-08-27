<?php

# 采用 Hook 原生 PHP 函数的方式实现协程客户端
# 通过一行代码就可以让原来的同步 IO 的代码变成可以协程调度的异步 IO，即一键协程化

# 设置加载协程Hook 
Co::set(['hook_flags' => SWOOLE_HOOK_FILE]);

$co = Co\run(function () {

	# 协程文件操作 4.3+版本后不推荐 【推荐使用一键协程化 Hook】
	// var_dump( Co\System::statvfs('/') );

	# 【一键协程化 Hook】
	// var_dump(__DIR__); // /www/wwwroot/swoole-mooc/io
    $file = __DIR__ . "/1.txt";
    $fp = fopen("1.txt", "r");
    // fwrite($fp, str_repeat('A', 2048));
    // fwrite($fp, str_repeat('B', 2048));
    
   	$content = fread($fp, filesize($file));
   	echo $content . PHP_EOL;
   	fclose($fp);

   	// 测试顺序运行
	echo "start" . PHP_EOL;

});