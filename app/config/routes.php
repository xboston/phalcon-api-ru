<?php

$router = new \Phalcon\Mvc\Router(false);

$router->notFound([ "controller" => "index" , "action" => "notFound" ]);
$router->removeExtraSlashes(true);

$router->add('/' , 'Index::index')->setName('index');

$router->add('/{pageSlug:(models|about|reference|team|roadmap|consulting|hosting|examples|support|ui)}' , [ 'controller' => 'Pages','action'=>'page'])->setName('pages');
$router->add('/download' , [ 'controller' => 'download' ])->setName('download');
$router->add('/documentation' , [ 'controller' => 'documentation' ])->setName('documentation');


$router->add('/api/{slug:[a-zA-Z/]+}' , [ 'controller' => 'api','action'=>'show' ])->setName('api');


$router->add('/test' , [ 'controller' => 'pages','action'=>'index' ])->setName('test');


return $router;
