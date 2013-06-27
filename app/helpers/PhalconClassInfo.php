<?php

class PhalconClassInfo
{

    public static function get($extra = false)
    {

        $allClasses = get_declared_classes();
        sort($allClasses);


        $classes   = [ ];
        $classData = [ ];

        foreach ( $allClasses as $className ) {

            if ( !preg_match('#^Phalcon#' , $className) ) {
                continue;
            }

            $classData['name']       = $className;
            $classData['extends']    = false;
            $classData['implements'] = false;

            if ( $extra ) {
                $reflector = new ReflectionClass($className);

                $extends              = $reflector->getParentClass();
                $classData['extends'] = $extends ? $extends->name : false;

                $implements              = $reflector->getInterfaceNames();
                $classData['implements'] = $implements ? $implements : false;
            }

            $classes[$className] = $classData;
        }

        return $classes;
    }

    public static function tree()
    {

        $allClasses = get_declared_classes();
        sort($allClasses);

        $trees = [ ];
        foreach ( $allClasses as $className ) {

            if ( !preg_match('#^Phalcon#' , $className) ) {
                continue;
            }

            $tree = explode('\\' , $className);

            switch ( count($tree) ) {

                case 2:

                    $trees[$tree[1]] = [ ];
                    break;

                case 3:

                    $trees[$tree[1]][$tree[2]] = [ ];
                    break;

                case 4:

                    $trees[$tree[1]][$tree[2]][$tree[3]] = [ ];
                    break;
            }
        }

        return $trees;
    }

}
