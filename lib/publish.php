<?php

include 'bootstrap.php';
include 'auth.php';
include 'user_panel.php';

use entities\Article;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pulls all the articles from the Database and shows them
 * @global Silex\Application $app
 * @return String 
 * @todo set a limit
 */
function get_articles($range = 0) {
    /* @var $app Silex\Application */
    global $app;
    
    //$collection = 'articles';
    $article = new Article();
    /* @var $cursor Doctrine\MongoDB\LoggableCursor */
        $cursor = $article->getRange($app, $range);
        $article_count = $cursor->count();
        $article_pos = $cursor->count(true);
        $end = false;
        if($article_pos < ARTICLE_LIMIT || (($range+1) * ARTICLE_LIMIT) == $article_count) {
            $end = true;
        }
        
    $posts = '';
    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }
    return $app['twig']->render('Articles.twig', array('articles' => $posts, 'nextpage' => $range + 1, 'end' => $end));
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

function del_article($id) {
    global $app;
    $article = new Article();
    return $article->delArticle($app, $id);
}

function post_article($postdata) {
    global $app;
    $article = new Article();
    $session = $app['session']->get('user');
    $poster = (isset($session['Author'])) ? $session['Author'] : $session['Name'];

    $article->setAuthor($poster);
    $article->setBody($postdata['Body']);

    if ($postdata['Tags']) {
        $tags = explode(';', $postdata['Tags']);
    }

    $article->setTags($tags);
    $article->setTitle($postdata['Title']);
    $article->setDate(time());
    $article->save($app);
}

/**
 * Starts running the Blog strongly depends on Silex functions
 * @global Silex\Application $app
 * @return void 
 */
function blog_run() {
    global $app;

    $app->get('/{pageName}', function($pageName, Silex\Application $app) {
                $page = '';
                switch ($pageName) {
                    case 'login' :
                        $page = show_login();
                        break;
                    case 'account' :
                        ($app['session']->get('user')) ? $page = show_account() : $page = $app->redirect('/');
                        break;
                    case 'logout':
                        $app['session']->clear();
                        $page = $app->redirect('/');
                        break;
                    case 'index' :
                        $page = get_articles();
                        break;
                    /**
                     * @todo That cast looks ugly, but it works at least 
                     */
                    case!is_null(preg_match('/\d+/', $pageName, $i)):
                        $page = get_articles($i[0]);
                        break;
                }
                return $page;
            })->value('pageName', 'index');

    $app->post('/{pageName}', function($pageName, Request $post, Silex\Application $app) {
                $page = '';
                $postdata = array();
                $postdata['Name'] = $post->get('Name');
                $postdata['Password'] = $post->get('Password');
                $postdata['Email'] = $post->get('Email');
                $postdata['Body'] = $post->get('Body');
                $postdata['Tags'] = $post->get('Tags');
                $postdata['Title'] = $post->get('Title');

                switch ($pageName) {
                    case 'login' :
                        do_login($postdata);
                        $page = show_account();
                        break;
                    case 'register':
                        if (!NOREG) {
                            $page = do_register($postdata);
                        } else {
                            $page = $app->redirect('/');
                        }
                        break;
                    case 'post_article':
                        post_article($postdata);
                        return $app->redirect('/');
                        break;
                    case 'del':
                        del_article($post->get('article_id'));
                        return $app->redirect('/');
                        break;
                }
                if ($page === false) {
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
                    })
            ->bind('article_url');

    $app->run();
}

?>