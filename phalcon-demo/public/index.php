<?php
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

session_start();

//【loader config file】

// Define some absolute path constants to aid in locating resources
define("BASE_PATH",dirname(__DIR__));
define("APP_PATH",BASE_PATH."/app");
require APP_PATH.'/common/functions.php';
// Register an autoloader
$loader = new Loader();
$loader->registerDirs([
    APP_PATH.'/controllers/',
    APP_PATH.'/models/',
]);
$loader->register();
// Create a DI
$di = new FactoryDefault();
// Setup the view component
$di->set('view',function (){
    $view = new View();
    $view->setViewsDir(APP_PATH.'/views/');
    //注册模板引擎
    $view->registerEngines(array(
        //设置模板后缀名
        '.phtml' => function ($view, $di){
            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            $volt->setOptions(array(
                //模板是否实时编译
                'compileAlways' => false,
                //模板编译目录
                'compiledPath' => APP_PATH . '/cache/compiled'
            ));
            return $volt;
        },
    ));
    return $view;
});
// Setup a base URI
$di->set('url',function (){
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
});
// Setup the database service
$di->set('db',function (){
    return new DbAdapter([
        'host'     => '192.168.1.127',
        'username' => 'root',
        'password' => '12345678',
        'dbname'   => 'phalcon',
        'port'     => '3306',
        'charset'  => 'utf8',
    ]);
});
$application = new Application($di);
try{
    // Handle the request
    $response = $application->handle();
    $response->send();
}catch (\Exception $e){
    echo 'Exception:',$e->getMessage();
}



