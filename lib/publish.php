<?php

include 'bootstrap.php';
include 'auth.php';

use entities\Article;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pulls all the articles from the Database and shows them
 * @global Silex\Application $app
 * @return String 
 * @todo set a limit
 */
function get_articles() {
    /* @var $app Silex\Application */
    global $app;

    $collection = 'articles';
    $article = new Article();
    /* @var $cursor Doctrine\MongoDB\LoggableCursor */
    $cursor = $article->getAll($app);

    $posts = '';
    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }

    return $app['twig']->render('Articles.twig', array('articles' => $posts));
}

/**
 * Gets a single article from the mongoDB
 * @global Silex\Application $app
 * @param type $title
 * @return String
 */
function get_article($title) {
    global $app;

    /** Revert Title */
    $original_title = str_replace('_', ' ', $title);
    $article = new Article();
    $oneArticle = $article->getOne($app, $original_title);
    return $app['twig']->render('Post.twig', array('a' => $oneArticle));
}

/**
 * Starts running the Blog strongly depends on Silex functions
 * @global Silex\Application $app
 * @return void 
 */
function blog_run() {
    global $app;

    $app->get('/{pageName}', function($pageName) {
                $page = '';
                switch ($pageName) {
                    case 'login' :
                        $page = show_login();
                        break;
                    case 'index' :
                        $page = get_articles();
                        break;
                }
                return $page;
            })->value('pageName', 'index');

    $app->post('/{pageName}', function($pageName, Request $post, Silex\Application $app) {
                $page = '';
                $postdata = array();
                $postdata['Name']     = $post->get('Name');
                $postdata['Password'] = $post->get('Password');
                
                switch ($pageName) {
                    case 'login' :
                        $page = do_login($postdata);
                        break;
                }
                if($page === false) {
                    /**
                     * Returns the User back to the Loginpage 
                     */
                    return $app->redirect('/login');
                } else {
                    return $page;
                }
            });


    $app->get('/article/{article_title}', function($article_title) {
                return get_article($article_title);
            });

    $app->run();
}

?>
