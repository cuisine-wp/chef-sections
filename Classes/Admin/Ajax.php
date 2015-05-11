<?php

	namespace ChefSections\Admin;

	use \ChefSections\Wrappers\SectionsBuilder;

	class Ajax{

		/**
		 * Sections bootstrap instance.
		 *
		 * @var \ChefSections
		 */
		private static $instance = null;


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->addEvents();

		}

		/**gatherSections
		 * Init the framework classes
		 *
		 * @return \ChefSections
		 */
		public static function getInstance(){

		    if ( is_null( static::$instance ) ){
		        static::$instance = new static();
		    }
		    return static::$instance;
		}



		/**
		 * All ajax events on the backend
		 * 
		 * @return [type] [description]
		 */
		private function addEvents(){


			add_action( 'wp_ajax_create_section', function(){

				echo SectionsBuilder::addSection();
				die();

			});
		}

	}


	if( is_admin() )
		\ChefSections\Admin\Ajax::getInstance();
