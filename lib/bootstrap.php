<?php
require 'silex.phar';

/** @var $app Silex\Application */
$app = new Silex\Application();

/**
 * Settins 
 */

/**
 * Turn Debug on or off 
 */
(DEBUG == 1) ? $app['debug'] = true : $app['debug'] = false;

/**
 * Twig as Tempatesystem 
 */

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../vendor/silex-extension/src');
$app['autoloader']->register();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
    'twig.path'     => __DIR__.'/../tmpl/'
));

use Symfony\Component\ClassLoader\UniversalClassLoader;
$loader = new UniversalClassLoader();
$loader->registerNameSpace('entities', __DIR__);
$loader->register();

/**
 * Symfony Validator Extension 
 */

$app->register(new Silex\Provider\ValidatorServiceProvider(), array(
    'validator.class_path' => __DIR__.'/../vendor/symfony/src'
));

/**
 * MongoDB 
 */
$app->register(new SilexExtension\MongoDbExtension(), array(
    'mongodb.class_path'    =>  __DIR__.'/../vendor/mongodb/lib',
    'mongodb.connection'    =>  array(
        'configuration' =>  function($configuration) {
        $configuration->setLoggerCallable(function($logs){
            (DEBUG == 1) ? print_r($logs) : null;
          });
        }
    )
));

/** 
 * Register some needed providers 
 * You can simply add more here
 */

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

?>
