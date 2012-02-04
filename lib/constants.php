<?php

/**
 * Enable or Disable Debug Mode here 
 */
define('DEBUG', 0);

/**
 * Your MongoDB used by the blog 
 */
define('MONGODB', 'blog');

/**
 * SALT used for Passwords, do not change it _after_ first registration  
 */
define('SALT', 'daov3nvsdkdd');

/**
 * Disable Registration 
 */
define('NOREG', 1);

/**
 * maximum articles per page, setting this to hight could produce mongodb timeouts 
 */
define('ARTICLE_LIMIT', 4);

/**
 * enables or disables Open Graph 
 */
define('OG', 1);

/**
 * enable or disable Feeds 
 */

define('FEEDS', 1);

/**
 * Your blog's name 
 */
define('BLOGNAME', 'My Blog');

define('BLOGURL', 'http://myblogurl.com');

define('BLOGSLOGAN', 'My Slogan');

define('BLOGDESCRIPTION', 'My Descritprion';

/**
 * Disqus is going to be optional in the Future
 */
define('DISQUS_SHORTNAME', 'mydisqusshortname');
?>
