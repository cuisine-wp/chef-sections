<?php
/**
 * Plugin Name: Cuisine Sections
 * Plugin URI: http://chefduweb.nl/cuisine
 * Description: Easily transform boring pages into exciting section-based layouts!
 * Version: 3.0.0
 * Author: Luc Princen
 * Author URI: http://get-cuisine.cooking/
 * License: GPLv2
 *
 * Text Domain: cuisinesections
 * Domain Path: /Languages/
 *
 * @package CuisineSections
 * @category Core
 * @author Cuisine
 */

namespace CuisineSections;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/**
 * Main class that bootstraps Chef Sections.
 */
if (!class_exists('CuisineSections')) {


    class CuisineSections{

        /**
         * Static bootstrapped CuisineSections instance.
         *
         * @var \CuisineSections\CuisineSections
         */
        public static $instance = null;


        /**
         * Sections version.
         *
         * @var float
         */
        const VERSION = '1.4.2';


        /**
         * Plugin directory name.
         *
         * @var string
         */
        private static $dirName = '';


        private function __construct(){

            static::$dirName = static::setDirName(__DIR__);

            // Load plugin.
            $this->load();

        }


        /**
         * Load the chef sections classes.
         *
         * @return void
         */
        private function load(){

            //load text-domain:
            $path = dirname( plugin_basename( __FILE__ ) ).'/Languages/';
            load_plugin_textdomain( 'cuisinesections', false, $path );

            //require the autoloader:
            require( __DIR__ . DS . 'autoloader.php');

            //initiate the autoloader:
            ( new \CuisineSections\Autoloader() )->register()->load();

            //new-up a deprecated class, to catch old filters & hooks:
            new \CuisineSections\Deprecated();


            do_action( 'cuisine_sections_loaded' );

        }




        /*=============================================================*/
        /**             Getters & Setters                              */
        /*=============================================================*/


        /**
         * Init the CuisineSections Class
         *
         * @return \CuisineSections\CuisineSections
         */
        public static function getInstance(){

            return static::$instance = new static();

        }


        /**
         * Set the plugin directory property. This property
         * is used as 'key' in order to retrieve the plugins
         * informations.
         *
         * @param string
         * @return string
         */
        private static function setDirName($path) {

            $parent = static::getParentDirectoryName(dirname($path));

            $dirName = explode($parent, $path);
            $dirName = substr($dirName[1], 1);

            return $dirName;
        }

        /**
         * Check if the plugin is inside the 'mu-plugins'
         * or 'plugin' directory.
         *
         * @param string $path
         * @return string
         */
        private static function getParentDirectoryName($path) {

            // Check if in the 'mu-plugins' directory.
            if (WPMU_PLUGIN_DIR === $path) {
                return 'mu-plugins';

            }

            // Install as a classic plugin.
            return 'plugins';
        }


        /**
         * Get the path for this plugin
         *
         * @return string
         */
        public static function getPluginPath(){
        	return __DIR__.DS;
        }

        /**
         * Returns the directory name.
         *
         * @return string
         */
        public static function getDirName(){
            return static::$dirName;
        }

    }
}


/**
 * Load the main class.
 *
 */
add_action('cuisine_loaded', function(){

	CuisineSections::getInstance();

}, 0, 200 );