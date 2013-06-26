<?php

/**
 * Class FileBack
 * Кеширование содержимого сайта в полную статику
 *
 */
class FileBack extends \Phalcon\Cache\Backend\File
{

    /**
     * При записи кеша создаём необходимую структуру каталогов
     *
     * @param null $keyName
     * @param null $content
     * @param null $lifetime
     * @param null $stopBuffer
     */
    public function save($keyName = null , $content = null , $lifetime = null , $stopBuffer = null)
    {

        $file = $keyName ? $keyName : $this->_lastKey;

        $location = dirname($this->_options['cacheDir'] . '/' . $file);

        is_dir($location) ? : mkdir($location , 0777 , true);

        return parent::save($keyName , $content , $lifetime , $stopBuffer);
    }

}
