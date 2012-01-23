<?php
    include 'bootstrap.php';
    use entities\Article;
    
    function get_articles() {
        /* @var $app Silex\Application */
        global $app;
        
        $collection = 'articles';
        $app->get('/', function() use($app, $collection) {
            $article = new Article();
            /* @var $cursor Doctrine\MongoDB\LoggableCursor */
            $cursor = $article->getAll($app);
            
            $posts = '';
            foreach($cursor->toArray() as $post) {
                /**
                 * Clean Code :)
                 */
                $posts[] = $post;
            }
            
            return $app['twig']->render('Articles.twig', array('articles' => $posts));
            
        });
    }
    
    function get_article($title){
        global $app;
        
        /** Revert Title */
        $original_title = str_replace('_', ' ', $title);
        $article = new Article();
        $oneArticle = $article->getOne($app,$original_title);
        return $app['twig']->render('Post.twig', array('a' => $oneArticle));
    }

    function blog_run() {
        global $app;
        //var_dump($app->get('/article/{id}', show_article($id))->assert('id', '\d+'));
        
        $app->get('/{pageName}', get_articles())->bind('home');
        $app->get('/article/{article_title}', function($article_title){
                    return get_article($article_title);
        });
        $app->run();
    }

?>
