<?PHP
require_once __DIR__.'/vendor/workerman/workerman/Autoloader.php';
use Workerman\Worker;
$worker = new Worker( 'websocket://0.0.0.0:443');
$worker->onWorkerStart= function($worker){
    echo 'on scuc';
};
$worker->onConnect=function ($connection){

    $connection_baidu = new \Workerman\Connection\AsyncTcpConnection("tcp://www.baidu.com:443");
    $connection_baidu->onMessage= function($connection_baidu,$data) use ($connection){
        $connection->send($data);
    };
    $connection->onMessage = function($connection,$data) use ($connection_baidu){
        $connection_baidu->send($data);
    };
    $connection_baidu->connect();
};
$worker->onMessage=function($conn,$data){
    $conn->send('hello world');
};
$worker->onClose=function($connection){
    echo 'close';
};
$worker->onWorkerStop=function( $worker){
    echo 'onWorkerstop success';
};
$worker::runAll();