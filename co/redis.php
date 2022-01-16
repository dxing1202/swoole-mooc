<?php

Co::set(['hook_flags' => SWOOLE_HOOK_TCP]);

Co\run(function() {
    // for ($c = 100; $c--;) {
    //     go(function () {//创建100个协程
    //         $redis = new Redis();
    //         $redis->connect('127.0.0.1', 6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
    //         $redis->get('key');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
    //     });
    // }
    
    go(function () {//创建100个协程
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
        // $redis->get('key');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
        
        // $redis->set('sky', null);
        
        // $key = $redis->keys('*');
        // $sky = $redis->get('sky');
        // $del = $redis->del('sky');
        // var_dump($sky);
        // var_dump($key);
        // var_dump($del);
    });
    
    echo "我先运行\n";
});