<?php

use Swoole\Process;

// $process = new Process();

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