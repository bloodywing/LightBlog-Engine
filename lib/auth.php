<?php
use entities\User;


$user = new User();

/**
 *
 * @param Silex\Application $app 
 */
function show_login() {
    global $app;
    return $app['twig']->render('User.twig', array('user' => $app['session']->get('user')));
}

/**
 *
 * @global Silex\Application $app
 * @global User $user
 * @param array $postdata 
 */
function do_register($postdata) {
    global $app, $user;
    
    $user->setName($postdata['Name']);
    $user->setPassword($postdata['Password']);
    $user->setEmail($postdata['Email']);
    $user->setAuthor($postdata['Author']);
    
    $userdata = $user->save($app);
    return $userdata;
}

/**
 *
 * @global Silex\Application $app
 * @global User $user
 * @return array 
 */
function do_login($postdata) {
    global $app, $user;
    $userdata = $user->getUserinfo($app, $postdata);
    
    if($userdata !== null && isset($userdata['Name'])) {
    /**
     * Session setzen 
     */
    $app['session']->set('user', $userdata);
      return true;
    } 
  return false;
}

?>
