<?php

try {
    /** @var \Phalcon\Config|stdClass $config */
    $config = include __DIR__ . '/app/config/config.php';
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(
        array(
             $config->application->tasksDir ,
             $config->application->helpersDir
        )
    )->register();

    $di = new \Phalcon\DI\FactoryDefault\CLI();

    $di->set(
        'log' ,
        function () {
            return new \Phalcon\Logger\Adapter\Stream('php://stdout');
        }
    );

    $di->set('config' , $config);

    //Set the views cache service
    $di->set(
        'viewCache' ,
        function () use ($config) {

            $frontCache = new \Phalcon\Cache\Frontend\Output([ "lifetime" => 86400 ]);

            $cache = new FileBack($frontCache , [ "cacheDir" => $config->application->cacheDir ]);

            return $cache;
        }
    );

    $di->set(
        'view' ,
        function () use ($config) {

            $view = new Phalcon\Mvc\View();
            $view->setViewsDir($config->application->viewsDir);

            return $view;
        } ,
        true
    );

    $dispatcher = new Phalcon\CLI\Dispatcher();
    $dispatcher->setDI($di);
    $dispatcher->setTaskName($argv[1]);
    isset($argv[2]) ? $dispatcher->setActionName($argv[2]) : null;
    $dispatcher->dispatch();

} catch ( Exception $e ) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
