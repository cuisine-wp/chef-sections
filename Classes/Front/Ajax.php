<?php

	namespace ChefSections\Front;

	use \ChefSections\Wrappers\Column;
	use \ChefSections\Wrappers\Template;
	use \stdClass;

	class Ajax{

		/**
		 * Ajax instance.
		 *
		 * @var \ChefSections\Front\Ajax
		 */
		private static $instance = null;


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->addAjaxEvents();

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
		 * All ajax events
		 * 
		 * @return string, echoed
		 */
		private function addAjaxEvents(){

			//creating a section:
			add_action( 'wp_ajax_autoload', array( &$this, 'handleAutoload' ) );
			add_action( 'wp_ajax_nopriv_autoload', array( &$this, 'handleAutoload' ) );

		}


		public function handleAutoload(){

			$this->setPostGlobal();

			$id = $_POST['column'];
			$section = $_POST['section'];

			$column = Column::collection( $id, $section );
			$column->setPage( $_POST['page'] );

			//first, check if there are posts:
			$q = $column->getQuery();
			if( !$q->have_posts() ){
				
				echo 'message';

			}else{
			
				Template::column( $column, $section )->display();
			
			}

			die();
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


	\ChefSections\Front\Ajax::getInstance();
