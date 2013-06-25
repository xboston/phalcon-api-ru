<?php


require_once 'class/EventsAwareInterface.php';
require_once 'class/InjectionAwareInterface.php';
require_once 'class/Console.php';

$className = 'Phalcon\CLI\Console';

$class = new ReflectionClass($className);


$method = $class->getMethod( 'handle' );
$param  = $method->getParameters();
$doc    = $method->getDocComment();


preg_match_all( '/@param\s([^\n]+)\s([$a-z]+)/is', $doc, $arr );

$params = array_combine($arr[2], $arr[1]);

preg_match( '/@return\s([^\n]+)/is', $doc, $arr );
print_r($arr);


//print_r($m);
