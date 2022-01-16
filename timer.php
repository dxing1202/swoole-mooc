<?php
declare(strict_types=1);

use function Swoole\Coroutine\run;
use function Swoole\Coroutine\go;

run(function() {
    go(function(){
        // swoole_timer_tick(1000, function() use ($str) {
        //     echo "Hello, $str\n";
        // });
        
        $str = "Swoole";
        swoole_timer_after(1000, function() use ($str) {
            echo "Hello, $str\n";
        });
        $pdo = new PDO('mysql:host=47.242.171.213;dbname=tiansuan;charset=utf8', 'tiansuan', 'KM4hAx4yG2rKaxpY');
        $statement = $pdo->prepare('SELECT * FROM `ts_test`');
        $statement->execute();
        var_dump($statement->fetchAll());
    });
});