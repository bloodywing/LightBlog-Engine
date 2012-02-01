<?php

use entities\Article;
use Symfony\Component\HttpFoundation\Response;

$article = new Article();
$cursor = $article->getAll($app);

    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }
return new Response($app['twig']->display('misc/rss.twig', array('articles' => $posts)), 
        200, 
        array('Content-Type' => 'application/rss+xml'));

?>
