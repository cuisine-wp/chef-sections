<?php

	namespace ChefSections\Admin;

	use \ChefSections\Wrappers\SectionsBuilder;
	use \ChefSections\Wrappers\Column;
	use \stdClass;

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


			add_action( 'wp_ajax_createSection', function(){

				$this->setPostGlobal();

				echo SectionsBuilder::addSection(  );
				die();

			});

			add_action( 'wp_ajax_saveColumnProperties', function(){

				echo Column::saveProperties();
				die();

			});
		}


		/**
		 * WordPress doesn't keep the post-global around, so we do it this way
		 *
		 * @return void
		 */
		private function setPostGlobal(){
			
			global $post;
			if( !isset( $post ) ){
				$GLOBALS['post'] = new stdClass();
				$GLOBALS['post']->ID = $_POST['post_id'];

			} 
		}


	}


	if( is_admin() )
		\ChefSections\Admin\Ajax::getInstance();
