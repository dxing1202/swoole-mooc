<?php

/**
 * 读取文件
 * __DIR__
 */
swoole_async_readfile(__DIR__."/1.txt", function($filename, $fileContent) {
	echo "filename:" . $filename . PHP_EOL;
	echo "content:" . $fileContent. PHP_EOL;
});