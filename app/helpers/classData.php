<?php
/**
 * Created by JetBrains PhpStorm.
 * User: boston
 * Date: 27.06.13
 * Time: 1:59
 * To change this template use File | Settings | File Templates.
 */

class classData extends  \Phalcon\DI\Injectable
{

    public function get($className)
    {



        if ( !class_exists($className) ) {

            return $this->notFoundAction();
        }

        //$className = "Phalcon\CLI\Console";

        $classData         = [ ];
        $classData['name'] = $className;

        $pieces          = explode("\\" , $className);
        $namespaceName   = join("\\" , array_slice($pieces , 0 , count($pieces) - 1));
        $normalClassName = join('' , array_slice($pieces , -1));

        $classData['namespace']       = $namespaceName;
        $classData['normalClassName'] = $normalClassName;

        //$simpleClassName = str_replace("\\" , "_" , $className);
        $simpleClassName = $className;

        $doccacheDir = $this->config->application->doccacheDir;
        $docLocation = $location = $doccacheDir . $simpleClassName . '.json';

        $classData['description'] = false;
        if ( is_file($docLocation) ) {

            $classDoc = $this->docCache($docLocation);

            $classData['description'] = $classDoc[$simpleClassName][$simpleClassName];
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
        if ( isset($classDoc[$simpleClassName]) ) {

            $docMethods = $classDoc[$simpleClassName];


        } else {

            $docMethods = [ ];
        }

        // methods
        $classData['methods'] = [ ];
        foreach ( $reflector->getMethods() as $method ) {

            $classData['methods'][$method->name]         = [ ];
            $classData['methods'][$method->name]['name'] = $method->name;

            $modifiers = Reflection::getModifierNames($method->getModifiers());
            if ( $reflector->isInterface() ) {
                $modifiers = array_intersect($modifiers , array( 'static' , 'public' ));
            }

            $classData['methods'][$method->name]['modifiers'] = $modifiers;

            $inherited = false;
            if ( $method->getDeclaringClass()->name != $reflector->name ) {

                $inherited = $method->getDeclaringClass()->name;
            }

            $classData['methods'][$method->name]['inherited'] = $inherited;

            $classData['methods'][$method->name]['description'] = false;

            if ( $inherited ) {

                $inheritedS = str_replace('\\' , '_' , $inherited);

                $inheritedLocation = $doccacheDir . $inheritedS . '.json';

                $classDocInherited   = $this->docCache($inheritedLocation);
                $docMethodsInherited = $classDocInherited[$inherited];

                $docMethodsInherited[$method->name] = str_replace(' Phalcon' , ' \Phalcon' , $docMethodsInherited[$method->name]);

                $docMethodsInherited                                = $docMethodsInherited[$method->name];
                $classData['methods'][$method->name]['description'] = $docMethodsInherited;

            } else {
                if ( isset($docMethods[$method->name]) ) {

                    $docMethods[$method->name] = str_replace(' Phalcon' , ' \Phalcon' , $docMethods[$method->name]);

                    $docMethod                                          = $docMethods[$method->name];
                    $classData['methods'][$method->name]['description'] = $docMethod;
                }
            }

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
        }

        return $classData;
    }


    /**
     * @param $docLocation
     *
     * @return mixed
     */
    private function docCache($docLocation)
    {
        $data     = file_get_contents($docLocation);
        $classDoc = json_decode($data , true);

        return $classDoc;
    }


}
