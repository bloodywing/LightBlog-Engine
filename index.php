<?php
    error_reporting(E_ALL);
    
    if(!isset($here)) {
        $here = __DIR__;
    }
    
    if(!defined('LIB')) {
        define('LIB', __DIR__.'/lib');
    }
    
    require LIB.'/constants.php';
    require LIB.'/publish.php';
    require LIB.'/additions.php';
    
    blog_run();
?>
