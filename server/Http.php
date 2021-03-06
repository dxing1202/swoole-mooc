<?php

# 技巧点
# netstat -anp | grep 8080
# 如果端口被占用，可以用以上的命令进行查询 是哪些程序|服务占用，Kill掉

# 使用协程风格服务
// use Swoole\Coroutine\Http\Server;
// use function Swoole\Coroutine\run;

/**
 * Http Swoole Server Class
 * @author dxing1202
 * @copyright dxing1202.cn
 * @since 2021-08-27
 */
class Http
{
    // Swoole\Server对象
    protected $serv = null;
    // 监听主机 对应外网的IP 0.0.0.0监听所有ip
    protected $host = '0.0.0.0';
    // 监听端口号 
    protected $port = 9501;

    public function __construct()
    {
        // run(function () {
            
        // });
        
        //创建WebSocket Server对象
        $this->serv = new Swoole\Http\Server($this->host, $this->port);
        // $this->serv = new Server($this->host, $this->port, false);
        // HTTP服务配置
        $this->serv->set([
            'enable_static_handler' => true,
            'document_root' => '/www/wwwroot/swoole-mooc/data'
        ]);

        $this->serv->on('request', [$this, 'onRequest']);

        // 执行异步任务回调函数
        // $this->serv->on('task', [$this, 'onTask']);
        // $this->serv->on('finish', [$this, 'onFinish']);

        $this->serv->start();
    }

    public function onRequest($request, $response)
    {
        // 解决Chrome 请求两次问题
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }

        // URL路由
        // list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
        //根据 $controller, $action 映射到不同的控制器类和方法
        // (new $controller)->$action($request, $response);

        # 日志写入
        // $logData = [
        //     'date:'  => date("Y-m-d H:i:s"),
        //     'get:'   => $request->get,
        //     'post:'  => $request->post,
        //     'header' => $request->header
        // ];
        // $this->saveRequestLog($data);

        // 设置header头部信息
        $response->header('Content-Type', 'text/html; charset=utf-8');
        // 打印GET请求
        // var_dump($request->get);
        // 设置cookie
        // $response->cookie("singwa", "xsss", time() + 1800);
        $response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . "<br/>GET: " . json_encode($request->get) . '</h1>');
    }

    protected function saveRequestLog($data)
    {
        # tail -f access.log
        # Linux 实时查看日志更新

        $date = date("Y-m-d");
        $filePath = __DIR__ . '/../logs/' . $date . ".log";

        # 设置加载协程Hook 
        Co::set(['hook_flags' => SWOOLE_HOOK_FILE]);

        Co\run(function () use ($filePath) {
            
            $fp = fopen($filePath, "a");

            #  clearstatcache()函数结果会被缓存，使用 clearstatcache() 来清除缓存
            // clearstatcache();
            // $fileSize = filesize($filePath);

            // $date = date("Ymd H:i:s") . PHP_EOL;

            $fwResult = fwrite($fp, json_encode($data) . PHP_EOL);

            if ( $fwResult ) {
                echo "success" . PHP_EOL;
            }

            fclose($fp);

        });

    }

}

$http = new Http();

# 测试端口
# curl 127.0.0.1:9501