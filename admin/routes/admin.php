<?php

use Shiva\Controllers\AuthController;
use Shiva\Middleware\RedirectIfNotAuthenticated;

$app->get('/register', function ($request, $response) {
    return $this->view->render($response, 'register.twig');
});

$app->post('/register', AuthController::class . ':register')->setName('auth.register');

$app->get('/login', function ($request, $response) {
    return $this->view->render($response, 'login.twig');
});

$app->post('/login', AuthController::class . ':login')->setName('auth.login');

$app->post('/logout', AuthController::class . ':logout')->setName('auth.logout');
    
$app->get('/dashboard', function ($request, $response) {
    return $this->view->render($response, 'admin/dashboard.twig');
})->add(new RedirectIfNotAuthenticated)->setName('dashboard');

/*
|--------------------------------------------------------------------------
| POST PATH
|--------------------------------------------------------------------------
*/

$app->post('/post', function() {
    $params = $_POST['weather'];

    file_put_contents('../data.php', $params);
})->setName('post');