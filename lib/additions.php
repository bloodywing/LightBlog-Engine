<?php

use Symfony\Component\HttpFoundation\Response;

$app->get("/main.css", function (Silex\Application $app) {
    return new Response($app['twig']->render('misc/css.twig'), 
        200, 
        array('Content-Type' => 'text/css'));
    
});


?>
