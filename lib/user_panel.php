<?php
//use entities\User;


//$user = new User();

function show_account() {
    global $app;
  var_dump($app['session']->get('user'));
    return $app['twig']->render('User_Panel.twig', array('user' => $app['session']->get('user')));
}
?>
