<?php

use Symfony\Component\HttpFoundation\Response;

$app->get("/css", function (Silex\Application $app) {
    return new Response($app['twig']->display('misc/css.twig'), 
        200, 
        array('Content-Type' => 'text/css'));
    
});


?>
