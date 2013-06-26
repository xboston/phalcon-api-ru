<?php

class Cdoc
{

    protected $_classDocs = [];

    public function _getDocs($file)
    {

        $firstDoc       = true;
        $openComment    = false;
        $nextLineMethod = false;
        $comment        = '';

        $doc = [ ];

        $className = false;
        foreach ( file($file) as $line ) {
            if ( trim($line) == '/**' ) {
                $openComment = true;
            }
            if ( $openComment === true ) {
                $comment .= $line;
            } else {
                if ( $nextLineMethod === true ) {
                    if ( preg_match('/^PHP_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                        $className = str_replace('_','\\',$matches[1]);
                        $doc[$className][$matches[2]] = $this->getPhpDoc($comment , $className);
                    } else {
                        if ( preg_match('/^PHALCON_DOC_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                            $className = str_replace('_','\\',$matches[1]);
                            $doc[$className][$matches[2]] = $this->getPhpDoc($comment , $className);
                        } else {
                            if ( $firstDoc === true ) {
                                $classDoc = $comment;
                                $firstDoc = false;
                                $comment  = '';
                            }
                        }
                    }
                    $nextLineMethod = false;
                } else {
                    $comment = '';
                }
            }
            if ( $openComment === true ) {
                if ( trim($line) == '*/' ) {
                    $openComment    = false;
                    $nextLineMethod = true;
                }
            }
            if ( preg_match('/^PHALCON_INIT_CLASS\(([a-zA-Z0-9\_]+)\)/' , $line , $matches) ) {
                $className = str_replace('_','\\',$matches[1]);
            }
        }

        if ( isset($classDoc) ) {
            if ( !isset($className) ) {
                return false;
            }
            if ( !isset($this->_classDocs[$className]) ) {
                $doc[$className][$className] = $this->getPhpDoc($classDoc , $className);
            }
        }

        return [ $className , $doc ];
    }


    private function getPhpDoc($phpdoc , $className = false)
    {

        $className = str_replace('_' , '\\' , $className);

        $description = [ ];

        $phpdoc = trim($phpdoc);

        foreach ( explode("\n" , $phpdoc) as $line ) {

            $line = preg_replace('#^/\*\*#' , '' , $line);
            $line = str_replace('*/' , '' , $line);
            $line = preg_replace('#^[ \t]+\*#' , '' , $line);
            $line = str_replace('*\/' , '*/' , $line);
            $line = preg_replace('#^[\t]\*#' , '' , $line);

            $trimLine = trim($line);

            if ( $trimLine != $className && $trimLine != '' ) {

                $description[] = $line;
            }
        }

        $description = implode(PHP_EOL,$description);

        //print_r($description);
        //die;
        /*
        preg_match_all("|<code([^>]*)>(.*?)<\/code\s*>|si" , $description , $codes);
        $code = isset($codes[2]) ? $codes[2] : false;
        */

        $descriptionFull = $description;
        $descriptionFull = preg_replace('/@([a-z0-9_-]+)([^\n]+)/is' , '' , $descriptionFull);

        $descriptionSmall = strtr($descriptionFull , [ "<code>\n" => '<div class="highlight"><pre>' , "\n</code>" => '</pre></div>' ]);

        $descriptionMini = preg_replace("|<code([^>]*)>(.*?)<\/code\s*>|si" , '' , $descriptionFull);
        $descriptionMini = str_replace("\n" , ' ' , $descriptionMini);


        preg_match_all('/@param\s([^\s]+)\s([^\n]+)/is' , $description , $arr);
        $params = array_combine($arr[2] , $arr[1]);

        preg_match('/@return\s([^\n]+)/is' , $description , $returns);
        $return = isset($returns[1]) ? $returns[1] : 'return';
///** 'full' => $descriptionFull */,
        return [ 'description1' => trim($descriptionSmall) , 'description_mini' => $descriptionMini ? $descriptionMini : false , 'params' => $params , 'return' => $return ];
    }

}
