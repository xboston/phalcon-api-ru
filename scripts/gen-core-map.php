<?php


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

$logger = new Phalcon\Logger\Adapter\Stream("php://stdout");

class Stubs_Generator
{

    protected $_docs = [ ];

    public function __construct($directory)
    {
        $this->_scanSources($directory);
    }

    protected function _scanSources($directory)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory , FilesystemIterator::SKIP_DOTS));
        foreach ( $iterator as $item ) {
            if ( $item->getExtension() == 'c' ) {
                if ( strpos($item->getPathname() , 'kernel') === false ) {
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
            }
            if ( $openComment === true ) {
                $comment .= $line;
            } else {
                if ( $nextLineMethod === true ) {
                    if ( preg_match('/^PHP_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                        $this->_docs[$matches[1]][$matches[2]] = trim($comment);
                        $className                             = $matches[1];
                    } else {
                        if ( preg_match('/^PHALCON_DOC_METHOD\(([a-zA-Z0-9\_]+), (.*)\)/' , $line , $matches) ) {
                            $this->_docs[$matches[1]][$matches[2]] = trim($comment);
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
                return null;
            }
            if ( !isset($this->_classDocs[$className]) ) {
                $this->_classDocs[$className] = $classDoc;
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

}

$version       = Phalcon\Version::get();
$versionPieces = explode(' ' , $version);
$genVersion    = $versionPieces[0];

$api = new Stubs_Generator(CPHALCON_DIR);

$classDocs = $api->getClassDocs();
$docs      = $api->getDocs();

$allClasses = array_merge(get_declared_classes() /* , get_declared_interfaces()*/);

// список интерфейсов
$interfaces = [ ];

// список классов
$classes = [ ];

foreach ( $allClasses as $className ) {

    if ( !preg_match('#^Phalcon#' , $className) ) {
        continue;
    }

    //$className = "Phalcon\CLI\Console";

    $logger->log($className);

    $classData         = [ ];
    $classData['name'] = $className;

    $pieces          = explode("\\" , $className);
    $namespaceName   = join("\\" , array_slice($pieces , 0 , count($pieces) - 1));
    $normalClassName = join('' , array_slice($pieces , -1));

    $classData['namespace']       = $namespaceName;
    $classData['normalClassName'] = $normalClassName;

    $simpleClassName = str_replace("\\" , "_" , $className);

    $simpleClassName = str_replace("\\" , "_" , $className);

    $classData['description'] = false;
    if ( isset($classDocs[$simpleClassName]) ) {

        $classDoc                 = helper::getPhpDoc($classDocs[$simpleClassName] , $className);
        $classData['description'] = $classDoc;
    }

    $reflector = new ReflectionClass($className);

    $classData['type'] = $reflector->isInterface() ? 'Interface' : 'Class';

    $typeClass = '';
    if ( !$reflector->isInterface() ) {

        $classes[] = $className;

        if ( $reflector->isAbstract() == true ) {
            $typeClass = 'abstract ';
        }
        if ( $reflector->isFinal() == true ) {
            $typeClass = 'final ';
        }

        $classData['typeClass'] = $typeClass;

    } else {

        $classData['typeClass'] = 'interface';
        $interfaces[]           = $className;
    }

    $extends = $reflector->getParentClass();
    if ( $reflector->isInterface() ) {

        if ( $extends ) {
            $classData['extends'] = $extends->name;
        } else {
            $classData['extends'] = false;
        }

    } else {

        $implements = $reflector->getInterfaceNames();
        if ( $extends ) {
            $classData['extends'] = $extends->name;

            //$logger->info('extends: ' . $reflector->name . ' - ' . $extends->name);
        } else {
            $classData['extends'] = false;
        }

        $classData['implements'] = false;
        if ( $implements ) {
            $classData['implements'] = $implements;
        }

    }

    // константы
    if ( $constants = $reflector->getConstants() ) {
        $classData['constants'] = $constants;
    } else {
        $classData['constants'] = false;
    }

    // public and protected properties
    $classData['properties'] = [ ];
    foreach ( $reflector->getProperties() as $property ) {
        if ( $property->getDeclaringClass()->name == $className ) {

            $classData['properties'][$property->name] = Reflection::getModifierNames($property->getModifiers());
        }
    }

    // документация по методам класса
    if ( isset($docs[$simpleClassName]) ) {

        $docMethods = $docs[$simpleClassName];
    } else {

        $docMethods = [ ];
    }

    // methods
    $classData['methods'] = [ ];
    foreach ( $reflector->getMethods() as $method ) {

        $classData['methods'][$method->name]         = [ ];
        $classData['methods'][$method->name]['name'] = $method->name;

        $classData['methods'][$method->name]['description'] = false;
        if ( isset($docMethods[$method->name]) ) {
            $docMethods[$method->name] = str_replace(' Phalcon' , ' \Phalcon' , $docMethods[$method->name]);

            $docMethod                                          = helper::getPhpDoc($docMethods[$method->name] , $method->name);
            $classData['methods'][$method->name]['description'] = $docMethod;
        }

        $modifiers = Reflection::getModifierNames($method->getModifiers());
        if ( $reflector->isInterface() ) {
            $modifiers = array_intersect($modifiers , array( 'static' , 'public' ));
        }
        $classData['methods'][$method->name]['modifiers'] = $modifiers;

        // параметры метода
        $parameters = [ ];
        foreach ( $method->getParameters() as $parameter ) {
            if ( $parameter->isOptional() ) {
                if ( $parameter->isDefaultValueAvailable() ) {
                    $parameters[] = '$' . $parameter->name . ' = ' . $parameter->getDefaultValue();
                } else {
                    $parameters[] = '$' . $parameter->name . ' = null';
                }
            } else {
                $parameters[] = '$' . $parameter->name;
            }
        }

        $classData['methods'][$method->name]['parameters'] = $parameters;


        $classData['methods'][$method->name]['inherited'] = false;
        if ( $method->getDeclaringClass()->name != $reflector->name ) {
            $classData['methods'][$method->name]['inherited'] = $method->getDeclaringClass()->name;
        }

    }

    $path = $genVersion . '/' . str_replace("\\" , DIRECTORY_SEPARATOR , $namespaceName);
    if ( !is_dir($path) ) {
        mkdir($path , 0777 , true);
    }

    ob_start();
    require("body-full.php");

    $source = ob_get_clean();

    file_put_contents($path . DIRECTORY_SEPARATOR . $normalClassName . '.html' , $source);
}

class helper
{

    public static function getPhpDoc($phpdoc , $className = false)
    {

        $description = '';

        $phpdoc = trim($phpdoc);

        foreach ( explode("\n" , $phpdoc) as $line ) {
            $line = preg_replace('#^/\*\*#' , '' , $line);
            $line = str_replace('*/' , '' , $line);
            $line = preg_replace('#^[ \t]+\*#' , '' , $line);
            $line = str_replace('*\/' , '*/' , $line);
            $line = preg_replace('#^[\t]\*#' , '' , $line);

            $description .= $line . PHP_EOL;
        }

        preg_match("|<code([^>]*)>(.*?)<\/code\s*>|si" , $description , $codes);
        $code = isset($codes[2]) ? $codes[2] : false;

        $descriptionFull = $description;
        $descriptionFull = preg_replace('/@([a-z0-9_-]+)([^\n]+)/is' , '' , $descriptionFull);

        $descriptionMini = preg_replace("|<code([^>]*)>(.*?)<\/code\s*>|si" , '' , $descriptionFull);
        $descriptionMini = str_replace("\n" , ' ' , $descriptionMini);

        preg_match_all('/@param\s([^\s]+)\s([^\n]+)/is' , $description , $arr);
        $params = array_combine($arr[2] , $arr[1]);

        preg_match('/@return\s([^\n]+)/is' , $description , $returns);
        $return = isset($returns[1]) ? $returns[1] : 'return';

        return [ 'full' => $descriptionFull , 'description' => trim($descriptionMini) , 'code' => $code , 'params' => $params , 'return' => $return ];
    }

}
