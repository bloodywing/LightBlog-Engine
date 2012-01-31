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
(DEBUG == 1) ? error_reporting(E_ALL) : error_reporting(E_WARNING);
/**
 * Twig as Tempatesystem 
 */

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../vendor/silex-extensions/src');
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
 * Markdown Support
 */
$app->register(new SilexExtension\MarkdownExtension(), array(
    'markdown.class_path' => __DIR__.'/../vendor/knplabs-markdown',
    'markdown.features' => array(
        'header' => true,
        'list' => true,
        'horizontal_rule' => true,
        'table' => true,
        'foot_note' => true,
        'fenced_code_block' => true,
        'abbreviation' => true,
        'definition_list' => true,
        'inline_link' => true,
        'reference_link' => true,
        'shortcut_link' => true,
        'block_quote' => true,
        'code_block' => true,
        'html_block' => true,
        'auto_link' => true,
        'auto_mailto' => true,
        'entities' => false
    )
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

/**
 * The Sessionprovider, based on Symfony 2 
 */
$app->register(new Silex\Provider\SessionServiceProvider());

?>
