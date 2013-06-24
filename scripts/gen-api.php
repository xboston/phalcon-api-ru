<?php

/**
 * This scripts generates the restructuredText for the class API.
 *
 * Change the CPHALCON_DIR constant to point to the dev/ directory in the Phalcon source code
 *
 * php scripts/gen-api.php
 */

if ( !extension_loaded('phalcon') ) {
    throw new Exception("Phalcon extension is required");
}

define('CPHALCON_DIR' , '/home/boston/gits/phalcon/core/ext/');


if ( !file_exists(CPHALCON_DIR) ) {
    throw new Exception("CPHALCON directory does not exist");
}

$version       = \Phalcon\Version::get();
$versionPieces = explode(' ' , $version);
$genVersion    = $versionPieces[0];

define('API_DIR' , dirname(__DIR__) . '/' . $genVersion . '/');
define('DOC_INDENT' , '   api/');

is_dir(API_DIR) ? : mkdir(API_DIR , 0777 , true);

class API_Generator
{

    protected $_docs = array();

    protected $_classDocs = array();

    /**
     * @var Phalcon\Logger\Adapter\Stream
     */
    private $logger;

    public function __construct($directory)
    {
        $this->logger = new Phalcon\Logger\Adapter\Stream("php://stdout");
        $this->_scanSources($directory);
    }

    protected function _scanSources($directory)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory , FilesystemIterator::SKIP_DOTS));
        foreach ( $iterator as $item ) {
            if ( $item->getExtension() == 'c' ) {
                if ( strpos($item->getPathname() , 'kernel') === false ) {

                    $this->logger->info($item->getPathname());

                    $this->_getDocs($item->getPathname());
                }
            }
        }
    }

    protected function _getDocs($file)
    {
        $firstDoc       = true;
        $openComment    = false;
        $nextLineMethod = false;
        $comment        = '';
        foreach ( file($file) as $line ) {
            if ( trim($line) == '/**' ) {
                $openComment = true;
                $comment .= $line;
            }
            if ( $openComment === true ) {
                $comment .= $line;
            } else {
                if ( $nextLineMethod === true ) {
                    if ( preg_match('/^PHP_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                        $this->_docs[$matches[1]][$matches[2]] = $comment;
                        $className                             = $matches[1];
                    } else {
                        if ( preg_match('/^PHALCON_DOC_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                            $this->_docs[$matches[1]][$matches[2]] = $comment;
                            $className                             = $matches[1];
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
                    $comment .= $line;
                    $openComment    = false;
                    $nextLineMethod = true;
                }
            }
            if ( preg_match('/^PHALCON_INIT_CLASS\(([a-zA-Z0-9\_]+)\)/' , $line , $matches) ) {
                $className = $matches[1];
            }
        }

        if ( isset($classDoc) ) {

            if ( !isset($className) ) {

                $fileName = str_replace(CPHALCON_DIR , '' , $file);
                $fileName = str_replace('.c' , '' , $fileName);

                $parts = array();
                foreach ( explode(DIRECTORY_SEPARATOR , $fileName) as $part ) {
                    $parts[] = ucfirst($part);
                }

                $className = 'Phalcon\\' . join('\\' , $parts);
            } else {
                $className = str_replace('_' , '\\' , $className);
            }

            if ( !isset($this->_classDocs[$className]) ) {
                if ( class_exists($className) or interface_exists($className) ) {
                    $this->_classDocs[$className] = $classDoc;
                }
            }
        }
    }

    public function getDocs()
    {
        return $this->_docs;
    }

    public function getClassDocs()
    {
        return $this->_classDocs;
    }

    public function getPhpDoc($phpdoc , $className)
    {

        $ret         = [ ];
        $description = '';

        $phpdoc = trim($phpdoc);
        $phpdoc = str_replace("\r" , "" , $phpdoc);

        foreach ( explode("\n" , $phpdoc) as $line ) {
            $line  = preg_replace('#^/\*\*#' , '' , $line);
            $line  = str_replace('*/' , '' , $line);
            $line  = preg_replace('#^[ \t]+\*#' , '' , $line);
            $line  = str_replace('*\/' , '*/' , $line);
            $tline = trim($line);

            preg_match('/@([a-z0-9]+)/' , $tline , $matches);

            if ( $className != $tline && (!isset($matches[1]) || ( $matches[1] != 'param' && $matches[1] != 'return' ) )) {
                $description .= $line . PHP_EOL;
            }
        }

        $description = strtr($description , [ '<code>' => '<div class="highlight"><pre>' , '</code>' => '</pre></div>' ]);

        $ret['description'] = $description;

        return $ret;
    }

}

$api = new API_Generator(CPHALCON_DIR);

$classDocs = $api->getClassDocs();
$docs      = $api->getDocs();

$classes = array();
foreach ( get_declared_classes() as $className ) {
    if ( !preg_match('#^Phalcon#' , $className) ) {
        continue;
    }
    $classes[] = $className;
}

foreach ( get_declared_interfaces() as $className ) {
    if ( !preg_match('#^Phalcon#' , $className) ) {
        continue;
    }
    $classes[] = $className;
}

sort($classes);


foreach ( $classes as $className ) {

    $classData = [ ];
    $code      = '';

    $realClassName = $className;

    $simpleClassName = str_replace("\\" , "_" , $className);
    $location        = API_DIR . str_replace("\\" , "/" , $className);

    is_dir($location) ? : mkdir($location , 0777 , true);

    $reflector = new ReflectionClass($className);

    $documentationData = array();

    $typeClass = 'public';

    if ( $reflector->isAbstract() == true ) {
        $typeClass = 'abstract';
    }

    if ( $reflector->isFinal() == true ) {
        $typeClass = 'final';
    }

    if ( $reflector->isInterface() == true ) {
        $typeClass = '';
    }

    $documentationData = array(
        'type'        => $typeClass ,
        'description' => $realClassName ,
        'extends'     => $reflector->getParentClass() ,
        'implements'  => $reflector->getInterfaceNames() ,
        'constants'   => $reflector->getConstants() ,
        'methods'     => $reflector->getMethods() ,
    );

    $nsClassName = str_replace("\\" , "\\\\" , $className);

    $classData['name']      = $className;
    $classData['type']      = $reflector->isInterface() == true ? 'Interface' : 'Class';
    $classData['typeClass'] = $typeClass;
    $classData['namespace'] = $reflector->getNamespaceName();


    $extends = false;
    if ( $documentationData['extends'] ) {
        $extendsName = $documentationData['extends']->name;

        // расширяет внутренний класс
        if ( strpos($extendsName , 'Phalcon') !== false ) {
            if ( class_exists($extendsName) ) {
                $extendsPath = str_replace("\\" , "_" , $extendsName);
                $extendsName = str_replace("\\" , "\\\\" , $extendsName);
                $extends     = 'phalcon-core: ' . $extendsName . ' => ' . $extendsPath;
            } else {
                $extends = 'phalcon-none: ' . $extendsName . PHP_EOL . PHP_EOL;
            }
        } else {
            $extends = 'php-net: ' . $extendsName . PHP_EOL . PHP_EOL;
        }
    }

    $classData['extends'] = $extends;

    // реализует
    $implements = false;
    if ( count($documentationData['implements']) ) {
        $implements = array();
        foreach ( $documentationData['implements'] as $interfaceName ) {
            if ( strpos($interfaceName , 'Phalcon') !== false ) {
                if ( interface_exists($interfaceName) ) {
                    $interfacePath = str_replace("\\" , "_" , $interfaceName);
                    $interfaceName = str_replace("\\" , "\\\\" , $interfaceName);
                    $implements[]  = 'phalcon-core: ' . $interfaceName . ' => ' . $interfacePath;
                } else {
                    $implements[] = str_replace("\\" , "\\\\" , $interfaceName);
                }
            } else {
                $implements[] = $interfaceName;
            }
        }
    }

    $classData['implements'] = $implements;

    $descriptionClass = false;
    if ( isset($classDocs[$realClassName]) ) {
        $ret         = $api->getPhpDoc($classDocs[$realClassName] , $className , null , $realClassName);
        $descriptionClass = $ret['description'];
    }

    $classData['description'] = $descriptionClass;


    $constants = [ ];
    if ( count($documentationData['constants']) ) {
        foreach ( $documentationData['constants'] as $name => $constant ) {
            $constants[$name] = gettype($constant);
        }
    }

    $classData['constants'] = $constants;

    $classData['methods'] = [ ];

    $methodData = false;
    if ( count($documentationData['methods']) ) {

        foreach ( $documentationData['methods'] as $method ) {

            $docClassName = str_replace("\\" , "_" , $method->getDeclaringClass()->name);
            if ( isset($docs[$docClassName]) ) {
                $docMethods = $docs[$docClassName];
            } else {
                $docMethods = array();
            }

            if ( isset($docMethods[$method->name]) ) {
                $ret = $api->getPhpDoc($docMethods[$method->name] , $className , $method->name , null);
            } else {
                $ret = array();
            }

            $methodData['types'] = array_flip(Reflection::getModifierNames($method->getModifiers()));

            $return = null;
            if ( isset($ret['return']) ) {
                if ( preg_match('/^(Phalcon[a-zA-Z0-9\\\\]+)/' , $ret['return'] , $matches) ) {
                    if ( class_exists($matches[0]) || interface_exists($matches[0]) ) {
                        $extendsPath = str_replace("\\" , "_" , $matches[1]);
                        $extendsName = str_replace("\\" , "\\\\" , $matches[1]);
                        $return      = str_replace($matches[1] , 'phalcon-core: ' . $extendsName . ' => ' . $extendsPath . ' ' , $ret['return']);
                    } else {
                        $extendsName = str_replace("\\" , "\\\\" , $ret['return']);
                        $return      = 'phalcon-none: ' . $extendsName;
                    }

                } else {
                    $return = 'php-net: ' . $ret['return'];
                }
            }
            $methodData['return'] = $return;
            $methodData['name']   = $method->name;

            $cp = array();
            foreach ( $method->getParameters() as $parameter ) {
                $name = '$' . $parameter->name;
                if ( isset($ret['parameters'][$name]) ) {
                    if ( strpos($ret['parameters'][$name] , 'Phalcon') !== false ) {
                        if ( class_exists($ret['parameters'][$name]) || interface_exists($ret['parameters'][$name]) ) {
                            $parameterPath = str_replace("\\" , "_" , $ret['parameters'][$name]);
                            $parameterName = str_replace("\\" , "\\\\" , $ret['parameters'][$name]);
                            if ( !$parameter->isOptional() ) {
                                $cp[] = ':doc:`' . $parameterName . ' <' . $parameterPath . '>` ' . $name;
                            } else {
                                $cp[] = '[:doc:`' . $parameterName . ' <' . $parameterPath . '>` ' . $name . ']';
                            }
                        } else {
                            $parameterName = str_replace("\\" , "\\\\" , $ret['parameters'][$name]);
                            if ( !$parameter->isOptional() ) {
                                $cp[] = '*' . $parameterName . '* ' . $name;
                            } else {
                                $cp[] = '[*' . $parameterName . '* ' . $name . ']';
                            }
                        }
                    } else {
                        if ( !$parameter->isOptional() ) {
                            $cp[] = '*' . $ret['parameters'][$name] . '* ' . $name;
                        } else {
                            $cp[] = '[*' . $ret['parameters'][$name] . '* ' . $name . ']';
                        }
                    }
                } else {
                    if ( $className != 'Phalcon\Kernel' ) {
                        if ( $simpleClassName == $docClassName ) {
                            //throw new Exception("unknown parameter $className::".$method->name."::".$parameter->name, 1);
                        }
                    }
                    if ( !$parameter->isOptional() ) {
                        $cp[] = '*unknown* ' . $name;
                    } else {
                        $cp[] = '[*unknown* ' . $name . ']';
                    }
                }
            }

            $methodData['parameters'] = join(', ' , $cp) . ')';


            $methodData['inherited'] = false;
            if ( $simpleClassName != $docClassName ) {
                $methodData['inherited'] = ' inherited from ' . str_replace("\\" , "\\\\" , $method->getDeclaringClass()->name);
            }

            $description = false;
            if ( isset($ret['description']) ) {
                foreach ( explode("\n" , $ret['description']) as $dline ) {
                    $description .= $dline;
                }
            } else {
                $description .= "...\n";
            }
            $methodData['description'] = $description;

            $classData['methods'][] = $methodData;
        }

    }


    ob_start();
    require("body-full.php");

    $data = ob_get_clean();

    file_put_contents('test.html' , $data);
    file_put_contents($location . '.html' , $data);

    continue;
    //die;

    file_put_contents($location . '.html' , $code);
}
