<?php

return new \Phalcon\Config([
                           'application' => [
                               'controllersDir' => __DIR__ . '/../../app/controllers/' ,
                               'viewsDir'       => __DIR__ . '/../../app/views/' ,
                               'tasksDir'       => __DIR__ . '/../../app/tasks/' ,
                               'helpersDir'     => __DIR__ . '/../../app/helpers/' ,
                               'cacheDir'       => __DIR__ . '/../../app/cache/' ,
                               'varDir'         => __DIR__ . '/../../app/var/' ,
                               'doccacheDir'    => __DIR__ . '/../../app/var/doccache/' ,
                               'baseUri'        => '/' ,
                               'sourceDir'      => '/home/boston/gits/phalcon/core/ext/'
                           ]
                           ]);
