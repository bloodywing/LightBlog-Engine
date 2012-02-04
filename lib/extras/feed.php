<?php

use entities\Article;
use Symfony\Component\HttpFoundation\Response;

$range = 0;

$article = new Article();
    /* @var $cursor Doctrine\MongoDB\LoggableCursor */
        $cursor = $article->getRange($app, $range);
        
    $posts = '';
    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }
return new Response($app['twig']->render('misc/rss.twig', array('articles' => $posts)), 
        200, 
        array('Content-Type' => 'application/rss+xml; charset=UTF-8'));

?>
