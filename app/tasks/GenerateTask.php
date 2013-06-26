<?php

/**
 *
 * @property \Phalcon\Logger\Adapter\Stream $log
 */
class GenerateTask extends \Phalcon\CLI\Task
{

    public function mainAction()
    {

        $this->log->info('Начали');

        $directory   = $this->config->application->sourceDir;
        $doccacheDir = $this->config->application->doccacheDir;

        $cParser = new Cdoc();

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory , FilesystemIterator::SKIP_DOTS));
        foreach ( $iterator as $item ) {
            if ( $item->getExtension() == 'c' ) {
                if ( strpos($item->getPathname() , 'kernel') === false ) {

                    $f = '/home/boston/gits/phalcon/core/ext/config/adapter/ini.c';
                    $f = $item->getPathname();

                    $docs = $cParser->_getDocs($f);

                    $this->log->info($f);

                    if ( $docs ) {

                        $location = $doccacheDir . str_replace('\\' , '_' , $docs[0]) . '.json';
                        file_put_contents($location , json_encode($docs[1]));
                    }
                }
            }
        }

        $this->log->info('Закончили');
    }

    public function runAction()
    {

        $this->log->info('Начали');

        $className = 'Phalcon\Config\Adapter\Ini';

        ob_start();

        $claData = new classData;
        $classData = $claData->get($className);


        $allClasses = get_declared_classes();
        foreach ( $allClasses as $className ) {

            if ( !preg_match('#^Phalcon#' , $className) ) {
                continue;
            }

            //$this->log->info($className);

            $this->view->cache([ "key" => str_replace('\\','/',$className).".html" ]);

            $this->view->setVar('classData' , $classData);
            $this->view->render('api' , 'show');
        }

        ob_clean();

        $this->log->info('Всё');
    }

}
