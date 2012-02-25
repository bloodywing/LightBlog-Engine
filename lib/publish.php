<?php

require 'bootstrap.php';
require 'auth.php';
require 'user_panel.php';

use entities\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pulls all the articles from the Database and shows them
 * @global Silex\Application $app
 * @return String 
 * @todo set a limit
 */
function get_articles($range = 0, $findby = array()) {
    /* @var $app Silex\Application */
    global $app;
    
    //$collection = 'articles';
    $article = new Article();
    /* @var $cursor Doctrine\MongoDB\LoggableCursor */
        $cursor = $article->getRange($app, $range, $findby);
        $article_count = $cursor->count();
        $article_pos = $cursor->count(true);
        $end = false;
        if($article_pos < ARTICLE_LIMIT || (($range+1) * ARTICLE_LIMIT) == $article_count) {
            $end = true;
        }
        
    $posts = null;
    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }
    
    return ($posts !== null) ? $app['twig']->render('Articles.twig', array('articles' => $posts, 'nextpage' => $range + 1, 'end' => $end, 'findby' => $findby )) : $app->redirect('/',301) ;
}

/**
 * Gets a single article from the mongoDB
 * @global Silex\Application $app
 * @param type $title
 * @return String
 */
function get_article($title, $id) {
    global $app;

    /** Revert Title */
    $original_title = urldecode($title);
    $article = new Article();
    $oneArticle = $article->getOne($app, '_id', new \MongoID($id));
    return $app['twig']->render('Post.twig', array('a' => $oneArticle));
}

/**
 *
 * @global Silex\Application $app
 * @param String $id
 * @return boolean
 */
function del_article($id) {
    global $app;
    $article = new Article();
    return $article->delArticle($app, $id);
}

function edit_article($id) {
    global $app;
    $article = new Article();
    return $app['twig']->render('User_Panel.twig',array('a' => $article->getOne($app, '_id', new \MongoId($id))));
}

function post_article($postdata, $update = false) {
    global $app;
    $article = new Article();
    $session = $app['session']->get('user');
    $poster = (isset($session['Author'])) ? $session['Author'] : $session['Name'];
    if($postdata['ID']) {
    $article->setId(new MongoID($postdata['ID']));
    } else {
    $article->setId(new MongoID());    
    }
    $article->setAuthor($poster);
    $article->setBody($postdata['Body']);
    
    if ($postdata['Tags']) {
        $tags = explode(';', $postdata['Tags']);
    }
    
    $article->setCategory($postdata['Category']);
    $article->setTags($tags);
    $article->setTitle($postdata['Title']);
    ($update)? $article->setDate(new MongoDate($postdata['Date'])) : $article->setDate(new MongoDate(time()));
    $article->save($app);
}

/**
 *
 * @global Silex\Application $app 
 */
function get_categories() {
    global $app;
    $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
    $cursor = $collection->find(array('Category' => array('$ne' => null)), array("Category" => 1));
        
        
    foreach ($cursor->toArray() as $category) {
        /**
         * Clean Code :)
         */
        $categories[] = $category['Category'];
    }
    
    $categories = array_unique($categories);
    
    return $categories;
}

function get_links() {
    global $app;
    $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'links');
    $cursor = $collection->find(array('Category' => array('$ne' => null)));
        
        
    foreach ($cursor->toArray() as $link) {
        $links[] = $link;
    }
    return $links;
}

/**
 *
 * @global Silex\Application $app
 * @param entities\Image $image
 * @return mixed 
 */
function get_image($imagename) {
    $image = new entities\Image();
    $result = $image->getOne($imagename);
    return new Response($result['Data']->bin, 200, array('Cache-Control' => 'cache, max-age=31536000',
                                                         'Expires' => gmdate("D, d M Y H:i:s", time() + 3600 * 24 * 356)." GMT",
                                                         'Last-Modified' => date(DATE_RSS, time() - 3600),
                                                         'Content-Type' => $result['Metadata']['Mime-Type']));
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
                    case 'logout':
                        $app['session']->clear();
                        $page = $app->redirect('/');
                        break;
                    case 'account' :
                    case 'acp':
                        return ($app['session']->get('user')) ? $page = show_account() : $page = $app->redirect('/');
                        break;
                    case 'index':
                        $page = get_articles(0, array('_id' => array('$ne' => null)));
                        break;
                    case 'sitemap.xml':
                        $page = include(__DIR__."/extras/sitemap.php");
                        break;
                    case 'rss.xml':
                        $page = include(__DIR__."/extras/feed.php");
                        break;
                }
                
                if(preg_match('/\d+/', $pageName, $i)) {
                        $page = get_articles($i[0], array('_id' => array('$ne' => null)));
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
                $postdata['Category'] = $post->get('Category');
                $postdata['Title'] = $post->get('Title');
                $postdata['Date'] = $post->get('Date');

                switch ($pageName) {
                    case 'login' :
                        do_login($postdata);
                        $page = $app->redirect('/account');
                        break;
                    case 'register':
                        if (!NOREG) {
                            $page = do_register($postdata);
                        } else {
                            $page = $app->redirect('/');
                        }
                        break;
                    case 'post_article':
                        ($app['session']->get('user')) ? post_article($postdata) : $page = $app->redirect('/');
                        return $app->redirect('/');
                        break;
                    case 'save_article':
                        $postdata['ID'] = $post->get('ID');
                        post_article($postdata, true);
                        return $app->redirect('/article/'.urlencode($postdata['Title']).'/'.$postdata['ID']);
                        break;
                    case 'del':
                        ($app['session']->get('user')) ? del_article($post->get('article_id')) : $page = $app->redirect('/');
                        return $app->redirect('/');
                        break;
                    case 'edit':
                        $page = edit_article($post->get('article_id'));
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
            
    $app->match('/admin/{subpage}/{task}', function(Request $request, $subpage, $task, Silex\Application $app){
       
    return adminfunctions($subpage, $task);            
    })->value('subpage', 'index')
      ->value('task', 'show');

    $app->get('/article/{article_title}/{id}', function($article_title, $id) {
                        return get_article($article_title, $id);
                    })
            ->bind('article_url');
                    
    $app->get('/category/{category}/{page}', function($category, $page, Silex\Application $app) {
            return get_articles($page, array('Category' => $category));
    })->value('page', '0')
      ->bind('category_url');

    /**
     * No Real Image Directory here 
     */
    $app->get('/images/{image}', function($image) {
        return get_image($image);
    })->bind('image');
    
    $app['categories'] = get_categories();                
    $app['links'] = get_links();
    $app->run();
}

?>
