<?php

/**
 * Enable or Disable Debug Mode here 
 */
define('DEBUG', 1);

/**
 * Your MongoDB used by the blog 
 */
define('MONGODB', 'blog');

/**
 * SALT used for Passwords, do not change it _after_ first registration  
 */
define('SALT', 'arrow_to_the_knee!!');

/**
 * Disable Registration 
 */
define('NOREG', 0);

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
define('BLOGNAME', 'Lightblog Engine Blog');

/**
 * CHANGE THIS
 * This is your Website URL 
 */
define('BLOGURL', 'http://tastyespresso.de');

/**
 * The Blog Slogan, currently only used inside the Templates. Look at Main.twig 
 */
define('BLOGSLOGAN', 'This is my awesome Blogslogan');

/**
 * Used in Meta Tags, inside Templates and Feeds 
 */
define('BLOGDESCRIPTION', 'This is a Blog, powered by Lightblog Engine');

/**
 * Your Disqus Shortname 
 */
define('DISQUS_SHORTNAME', 'changeme');
?>
