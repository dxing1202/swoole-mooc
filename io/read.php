<?php

Co::set(['hook_flags' => SWOOLE_HOOK_FILE]);

Co\run(function () {
    $fp = fopen("1.txt", "r");
    // fwrite($fp, str_repeat('A', 2048));
    // fwrite($fp, str_repeat('B', 2048));
    var_dump(__DIR__);
    $file = __DIR__ . "/1.txt";
    echo $file . PHP_EOL;
   	$content = fread($file, filesize($file));
   	echo $content;
   	fclose($file);
});