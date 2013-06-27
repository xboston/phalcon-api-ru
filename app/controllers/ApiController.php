<?php

class ApiController extends ControllerBase
{

    public function showAction($class)
    {
        $className = str_replace('/' , '\\' , $class);
        $className = 'Phalcon\\' . $className;

        $claData   = new classData;
        $classData = $claData->get($className);

        $this->view->setVar('classData' , $classData);

        $tree = PhalconClassInfo::tree();

        $this->view->setVar('tree', $tree);
    }

    public function treeAction()
    {

        $classes = PhalconClassInfo::get();

        $this->view->setVar('classes' , $classes);
    }
}

