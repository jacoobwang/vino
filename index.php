<?php
/**
 * Created by PhpStorm.
 * User: Jacoob
 * Date: 7/10/16
 * Time: 10:50 AM
 */
namespace Ecc\Topic;
error_reporting(E_ALL);

define('SITE_ROOT', __DIR__);
define('DEBUG', true);
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

    // smarty
    $di->register('view', function () use($di) {
        $cfg = $di['config'];
        $view =  new \Smarty();
        $view->setTemplateDir(SITE_ROOT . '/' . $cfg->get('smarty/tpl_dir'));
        $view->setCompileDir($cfg->get('smarty/compile_dir'));
        $view->setLeftDelimiter('{{');
        $view->setRightDelimiter('}}');
        return $view;
    });
}

// redis 默认关闭
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


$di->register('router', function () use($di) {
    $base_url     = $di['config']->get('core/base_url');
    $route_style  = $di['config']->get('core/route_style');

    define('BASE_URL', $base_url);

    $router = new \Mphp\Router($base_url,$route_style);

    $router->addRoutes(
        [
            'article/{id}'       => 'IndexController/del',
            'reg'                => 'UserController/user',
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
        ],
        [
            'middleware'  => 'AuthMiddleware',
        ]
    );

    return $router;
});

$di->register('db', function () {
    return \Mphp\Db::getConnection();
});

$app->run($di);
