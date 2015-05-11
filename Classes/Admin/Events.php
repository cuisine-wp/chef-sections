<?php

	namespace ChefSections\Admin;

	use \ChefSections\Wrappers\SectionsBuilder;
	use \ChefSections\Admin\Ajax;
	use \Cuisine\Utilities\Url;

	class Events{

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

			$this->adminEvents();
			$this->adminEnqueues();

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
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function adminEnqueues(){


			add_action( 'admin_init', function(){

				$url = Url::plugin( 'chef-sections', true ).'assets';
				wp_enqueue_script( 'sections', $url.'/js/Section.js' );
				wp_enqueue_script( 'sections', $url.'/js/Column.js' );
				wp_enqueue_style( 'sections', $url.'/css/admin.css' );
				
			});
		}


		/**
		 * All admin actions
		 * @return [type] [description]
		 */
		private function adminEvents(){

			add_action( 'edit_form_after_editor', function(){

				SectionsBuilder::build();

			});

		}

	}


	\ChefSections\Admin\Events::getInstance();
