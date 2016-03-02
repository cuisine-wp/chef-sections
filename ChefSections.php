<?php
/**
 * Plugin Name: Chef Sections
 * Plugin URI: http://chefduweb.nl/cuisine
 * Description: Easily transform boring pages into exciting section-based layouts!
 * Version: 1.3.6
 * Author: Luc Princen
 * Author URI: http://www.chefduweb.nl/
 * License: GPLv2
 * Bitbucket Plugin URI: https://LucPrincen@bitbucket.org/chefduweb/chef-sections
 * Bitbucket Branch:     master
 * 
 * @package ChefSections
 * @category Core
 * @author Chef du Web
 */

namespace ChefSections;

use Cuisine\Wrappers\StaticInstance;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/**
 * Main class that bootstraps Chef Sections.
 */
if (!class_exists('ChefSections')) {


    class ChefSections{

        /**
         * Static bootstrapped ChefSections instance.
         *
         * @var \ChefSections\ChefSections
         */
        public static $instance = null;


        /**
         * Sections version.
         *
         * @var float
         */
        const VERSION = '1.3.6';


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

            //auto-loads all .php files in these directories.
            $includes = array(
                'Classes/Wrappers',
                'Classes/Columns',
                'Classes/Builders',
                'Classes/Sections',
                'Classes/Hooks',
                'Classes/Admin',
                'Classes/Front'
            );

            $includes = apply_filters( 'chef_sections_autoload_dirs', $includes );


            foreach( $includes as $inc ){
                
                $root = static::getPluginPath();
                $files = glob( $root.$inc.'/*.php' );

                foreach ( $files as $file ){

                    require_once( $file );

                }
            }

            do_action( 'chef_sections_loaded' );

        }




        /*=============================================================*/
        /**             Getters & Setters                              */
        /*=============================================================*/


        /**
         * Init the ChefSections Class
         *
         * @return \ChefSections\ChefSections
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

	ChefSections::getInstance();

}, 0, 200 );