<?php
/**
 * Swoole 一键协程化 MySQL
 * 使用PDO-MySQL
 */
use function Swoole\Coroutine\run;
use function Swoole\Coroutine\go;

run(function () {
    go(function () {

    	$host = '127.0.0.1';
    	$dbname = 'tiansuan';
    	$charset = 'utf8';
    	$uname = 'tiansuan';
    	$pass = 'KM4hAx4yG2rKaxpY';

        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $uname, $pass);
        $statement = $pdo->prepare('SELECT * FROM `tiansuan`');
        $statement->execute();
        var_dump($statement->fetchAll());
    });
});