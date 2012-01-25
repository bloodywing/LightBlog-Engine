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
 * @return array 
 */
function do_login($postdata) {
    global $app, $user;
    $userdata = $user->getUserinfo($app, $postdata);
    
    if($userdata !== null && isset($userdata['Name'])) {
    /**
     * Session setzen 
     */
    $app['session']->set('user', array('username' => $userdata['Name']));
      return true;
    } 
  return false;
}

?>
