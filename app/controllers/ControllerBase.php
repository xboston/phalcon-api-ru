<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function notFoundAction()
    {
        $this->response->setHeader(404 , 'Not Found');
        $this->view->pick('404/404');
    }

}
