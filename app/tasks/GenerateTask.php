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

                        $location = $doccacheDir . str_replace('\\','_', $docs[0]) . '.json';
                        file_put_contents($location , json_encode($docs[1]));
                    }
                }
            }
        }

        $this->log->info('Закончили');
    }
}
