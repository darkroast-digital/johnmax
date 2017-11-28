<?php

namespace Shiva\Controllers;

class AuthController extends Controller
{

    public function register($request, $response)
    {
        if(!isset($_POST['username']) || !isset($_POST['password']));

        $params = $request->getParams();
        $email = $params['email'];
        $username = $params['username'];
        $password = $params['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $params['password'] = $hashed_password;

        $json = json_encode($params, JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/../../resources/content/admin/users/' . $username . '.json', $json);

        return $response->withRedirect('/login');
    }

    public function login($request, $response)
    {
        if(!isset($_POST['username']) || !isset($_POST['pass'])){
          //Redirect somewhere 
        } 

        $params = $request->getParams();
        $username = $params['username'];
        $password = $params['password'];

        $fileName = __DIR__ . "/../../resources/content/admin/users/". $username  . ".json"; 
        $fileExists = file_exists($fileName);
        if ($fileExists) {
            $fileContent = file_get_contents($fileName);
        } else {
            print 'Please Register';
        }
        $creds = json_decode($fileContent, true);

        $hashed_password = $creds['password'];

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user'] = $username; 
            $_SESSION['logged'] = "yes";

            session_regenerate_id(true);
            session_write_close();
            return $response->withRedirect($this->c->router->pathFor('dashboard'));
            exit;
        }
        else {
            print 'Username or password was incorrect';
        }
        die();     
    }

    public function logout($request, $response)
    {
        session_start();
        unset($_SESSION);
        session_destroy();
        $_SESSION = array();

        return $response->withRedirect('/');
        die();
    }
}