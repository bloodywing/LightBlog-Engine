<?php
    error_reporting(E_ALL);
    
    if(!isset($here)) {
        $here = __DIR__;
    }
    
    if(!defined('LIB')) {
        define('LIB', __DIR__.'/lib');
    }
    
    include LIB.'/constants.php';
    include LIB.'/publish.php';
    
    blog_run();
?>
