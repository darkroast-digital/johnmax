<?php

namespace Shiva\Controllers;

class PagesController extends Controller
{
    public function index($response, $request)
    {
        var_dump($response);
        die();
        return $this->view->render($response, 'home.twig');
    }

    public function users()
    {
        return 'Users';
    }

}