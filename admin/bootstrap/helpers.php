<?php

$twig = $container->view-> getEnvironment();

// CSS Getter

$css = new Twig_SimpleFunction('css', function ($css) {
    return '/../resources/assets/css/'. $css . '.css';
});
$twig->addFunction($css);

// JS Getter

$js = new Twig_SimpleFunction('js', function ($js) {
    return '/../resources/assets/js/'. $js . '.js';
});
$twig->addFunction($js);

// IMG Getter

$img = new Twig_SimpleFunction('img', function ($img) {
    return '/../resources/assets/img/'. $img;
});
$twig->addFunction($img);

// Snippet Getter

$snippet = new Twig_SimpleFunction('snippet', function ($snippet) {
    include __DIR__ . '/../resources/views/snippets/'. $snippet . '.php';
});
$twig->addFunction($snippet);

// Page Name

$pagename = new Twig_SimpleFunction('pagename', function () {
    echo basename($_SERVER['SCRIPT_NAME']);
});
$twig->addFunction($pagename);

//$twig->addGlobal('page', $page);