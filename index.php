<?php
/**
 * Created by PhpStorm.
 * User: Jacoob
 * Date: 7/10/16
 * Time: 10:50 AM
 */
namespace Ecc\Topic;
//error_reporting(E_ALL);

define('SITE_ROOT',  __DIR__);
define('DEBUG',     true);
require __DIR__ . '/vendor/autoload.php';


$app = \Mphp\App::getSingleton();
$app->setControllerNamespace('\\Ecc\\Topic\\Controller\\');
$di = $app->di();

$di->register('config', function () {
    return new \Mphp\Config(SITE_ROOT.'/configs');
});

if (!defined('IN_CLI')) {
    // session
    $di->register('session', function() {
        return new \Mphp\Session();
    });

    // twig
    $di->register('twig', function () use($di) {
        $cfg = $di['config'];
        $loader =  new \Twig_Loader_Filesystem(SITE_ROOT . '/' . $cfg->get('twig/tpl_dir'));
        $twig    =  new \Twig_Environment($loader, array(
            'cache' => $cfg->get('twig/compile_dir'),
        ));
        return $twig;
    });

    // monolog
    $di->register('log', function () use($di) {
        $log = new \Mphp\MLogger(SITE_ROOT.'/logs/app.log');
        $log->setWebProcessor();   // 请求相关信息
        //$log->setAllowChromeLog(); //将日志输出到chrome控制台
        return $log;
    });
}


// redis 如果本地没有安装redis，请注释掉本段代码
$di->register('redis',function() use($di) {
    $cfg = $di['config']->get('redis');
    $inst = new \Redis();
    $host= $cfg['default']['host'];
    $port= $cfg['default']['port'];
    $auth= $cfg['default']['auth'];
    if (!empty($cfg['auth'])) {
        $inst->auth($auth);
    }
    $inst->connect($host, $port);
    return $inst;
});

// 路由
$di->register('router', function () use($di) {
    $base_url     = $di['config']->get('core/base_url');
    $route_style  = $di['config']->get('core/route_style');

    define('BASE_URL', $base_url);

    $router = new \Mphp\Router($base_url,$route_style);

    $router->addRoutes(
        [
            'article/{id}'       => 'IndexController/del',
            'login'              => 'UserController/user',
            'logout'             => 'UserController/logout',   
        ]
    );

    $router->addRoutes(
        [
            'api/user/login'     => 'UserController/login',
            'api/user/reg'       => 'UserController/reg',
        ],
        [
            'middleware'  => 'CsrfMiddleware',
        ]
    );

    $router->addRoutes(
        [
            'api/user/{id}'      => 'UserController/getUserInfo',
            'userInfo'           => 'UserController/userCenter'
        ],
        [
            'middleware'  => 'AuthMiddleware',
        ]
    );

    return $router;
});

// db 
$di->register('db', function () {
    return \Mphp\Db::getConnection();
});

$app->run($di);
