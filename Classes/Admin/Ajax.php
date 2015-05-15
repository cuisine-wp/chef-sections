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

			$this->addSectionEvents();
			$this->addColumnEvents();

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
		 * All ajax events for sections on the backend
		 * 
		 * @return string, echoed
		 */
		private function addSectionEvents(){


			//creating a section:
			add_action( 'wp_ajax_createSection', function(){

				$this->setPostGlobal();

				echo SectionsBuilder::addSection();
				die();

			});

			//sorting sections:
			add_action( 'wp_ajax_sortSections', function(){

				$this->setPostGlobal();

				echo SectionsBuilder::sortSections();
				die();

			});

			//change a sections' view:
			add_action( 'wp_ajax_changeView', function(){

				$this->setPostGlobal();

				echo SectionsBuilder::changeView();
				die();

			});

		}


		/**
		 * Alle ajax events for columns on the backend 
		 *
		 * return string, echoed
		 */
		private function addColumnEvents(){

			add_action( 'wp_ajax_saveColumnType', function(){

				$this->setPostGlobal();

				Column::saveType();
				die();

			});



			//save lightbox data:
			add_action( 'wp_ajax_saveColumnProperties', function(){

				$this->setPostGlobal();

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
