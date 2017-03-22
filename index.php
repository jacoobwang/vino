<?php
/**
 * Created by PhpStorm.
 * User: Jacoob
 * Date: 7/10/16
 * Time: 10:50 AM
 */
namespace Ecc\Topic;

defined('SITE_ROOT') or define('SITE_ROOT',  __DIR__);
defined('TIMESTAMP') or define('TIMESTAMP', time());
defined('Vino_DEBUG') or define('Vino_DEBUG', true);

require __DIR__ . '/vendor/autoload.php';

ini_set('date.timezone','Asia/Shanghai');

$app = \Vino\App::getSingleton();
$app->setControllerNamespace('\\Ecc\\Topic\\Controller\\');
$di = $app->di();

$di->register('config', function () {
    return new \Vino\Config(SITE_ROOT.'/configs');
});

if (!defined('IN_CLI')) {
    // session
    $di->register('session', function() {
        return new \Vino\Session();
    });

    // twig
    $di->register('twig', function () use($di) {
        $cfg = $di['config'];
        $loader =  new \Twig_Loader_Filesystem(SITE_ROOT . '/' . $cfg->get('twig/tpl_dir'));
        $twig    =  new \Twig_Environment($loader, array(
            'cache' => $cfg->get('twig/compile_dir'), //缓存目录
            'auto_reload' => true     //代码改变，重新编译
        ));
        return $twig;
    });

    // monolog
    $di->register('log', function () use($di) {
        $log = new \Vino\MLogger(SITE_ROOT.'/logs/app.log');

        // header info if no need,you can remove
        $log->setWebProcessor();
        return $log;
    });
}

// routers
$di->register('router', function () use($di) {
    $base_url     = $di['config']->get('core/base_url');
    $route_style  = $di['config']->get('core/route_style');

    defined('BASE_URL') or define('BASE_URL', $base_url);

    $router = new \Vino\Router($base_url,$route_style);

    $router->addRoutes(
        [
            'userInfo'           => 'UserController/userCenter',
            'adminHome'          => 'UserController/homeCenter', 
            'adminLab'           => 'UserController/labCenter',  
            'adminAll'           => 'UserController/allCenter',    
        ]
    );

    return $router;
});

// db 
$di->register('db', function () {
    return \Vino\Db::getConnection();
});

// 错误捕获
if(Vino_DEBUG){
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$app->run($di);
