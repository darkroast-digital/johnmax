<?php

/*
|--------------------------------------------------------------------------
|  Web Routes
|--------------------------------------------------------------------------
*/

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'login.twig');
})->setName('home');

$app->post('/home/contact', function ($request, $response) {

    $mail = new PHPMailer;
    
    $name = $_POST['name'];

    $mail->setFrom($name);
    $mail->addAddress('joshstobbs@gmail.com');
    $mail->addReplyTo($name);

    $mail->isHTML(true);

    $mail->Subject = 'A new message from' . $name;
    $mail->Body = "Name: $name";
    $mail->AltBody = "Name: $name";

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
})->setName('home.contact');