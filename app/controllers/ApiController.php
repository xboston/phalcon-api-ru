<?php

class ApiController extends ControllerBase
{

    public function showAction($class)
    {
        $className = str_replace('/' , '\\' , $class);
        $className = 'Phalcon\\' . $className;

        $claData = new classData;
        $classData = $claData->get($className);
        /*
        echo '<pre>';
        print_r($classData);
        die;
*/
        $this->view->setVar('classData' , $classData);
    }

}

