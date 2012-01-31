<?php

/**
 * $app is available here
 * That gives you access to twig, mongo and all the other
 * stuff - so lets be happy with that 
 */

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
return new Response($app['twig']->display('misc/sitemap.twig', array('articles' => $posts)), 
        200, 
        array('Content-Type' => 'text/xml'));
?>
