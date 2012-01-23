<?php
include 'bootstrap.php';

/* @var $app Silex\Application */
$app->get('/navigation', function() use($app) {
    return $app['twig']->render('Navigation.twig',array(
        'navlinks' => array($app['url_generator']->generate('Home'))
    ));
});
?>
