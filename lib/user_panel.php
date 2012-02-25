<?php

//global $page, $app;

function show_account() {
    global $app;
    return $app['twig']->render('User_Panel.twig', array('user' => $app['session']->get('user')));
}

function adminfunctions($subpage, $task) {
    global $app;
    
    /**
     * Redirects not logged in Users back index. 
     */
    if(!$app['session']->get('user'))
        return $app->redirect('/');
                        
    if(!is_null($subpage)) {
        /**
         * Because we don't need another huge switch here so lets make your functions more
         * generic. 
         */
        return call_user_func("${task}_${subpage}");
    }
}

function show_links() {
    global $app;
    $links = new entities\Link();
    $cursor = $links->getRange($app, 0);
    $posts = array();
    
    foreach ($cursor->toArray() as $post) {
        /**
         * Clean Code :)
         */
        $posts[] = $post;
    }
    return $app['twig']->render('admin/admin_links.twig', array('links' => $posts));
}

function add_links() {
    global $app;
    $link = new entities\Link();
    $link->setTarget($app['request']->get('Target'));
    $link->setCategory($app['request']->get('Category'));
    $link->setTitle($app['request']->get('Title'));
    
    $link->save($app);
    return $app->redirect('/admin/links');
}

function del_link() {
    global $app;
    $link = new entities\Link();
    $link->setId($app['request']->get('link_id'));
    $link->delLink($app, $link->getId());
    return $app->redirect('/admin/links');
}

/**
 * Stuff with Images goes here
 */


function show_images() {
    global $app;
    $images = new entities\Image();
    $cursor = $images->getRange($app, 0);
    $posts = array();
    
    foreach ($cursor->toArray() as $post) {
        $posts[] = $post;
    }
    return $app['twig']->render('admin/admin_images.twig', array('images' => $posts));
}


function add_images() {
    global $app;
    $image = new entities\Image();
    $file = $app['request']->files->get('File');
    $originalfile = $file->getRealPath();
    $filedata = file_get_contents($originalfile);
    
    $image->setData($filedata);
    $image->setFilename($file->getClientOriginalName());
    $image->setMetadata(array('Mime-Type' => $file->getMimeType()));
    $image->setType('image');
    
    $image->save($app);
    return $app->redirect('/admin/images');
}

function del_image() {
    global $app;
    $image = new entities\Image();
    $image->setId($app['request']->get('image_id'));
    $image->delImage($app, $image->getId());
    return $app->redirect('/admin/images');
}

?>
