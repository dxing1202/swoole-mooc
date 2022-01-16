<?php
/**
 * Swoole 一键协程化 MySQL
 * 使用PDO-MySQL
 */
// use function Swoole\Coroutine\run;
// use function Swoole\Coroutine\go;

// run(function () {
//     go(function () {

//     	$host = '127.0.0.1';
//     	$dbname = 'tiansuan';
//     	$charset = 'utf8';
//     	$uname = 'tiansuan';
//     	$pass = 'KM4hAx4yG2rKaxpY';

//         $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $uname, $pass);
//         $statement = $pdo->prepare('SELECT * FROM `ts_test`');
//         $statement->execute();
//         var_dump($statement->fetchAll());
//     });
// });

Co::set(['hook_flags' => SWOOLE_HOOK_TCP]);

Co\run(function() {
    // for ($c = 100; $c--;) {
    //     go(function () {//创建100个协程
    //         $redis = new Redis();
    //         $redis->connect('127.0.0.1', 6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
    //         $redis->get('key');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
    //     });
    // }
    
    go(function () {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=tiansuan;charset=utf8', 'tiansuan', 'KM4hAx4yG2rKaxpY');
        $statement = $pdo->prepare('SELECT * FROM `ts_test`');
        $statement->execute();
        var_dump($statement->fetchAll());
    });
    
    echo "我先运行 \n";
});