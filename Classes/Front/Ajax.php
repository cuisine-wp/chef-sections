<?php

	namespace ChefSections\Front;

	use ChefSections\Wrappers\AjaxInstance;
	use ChefSections\Wrappers\Column;
	use ChefSections\Wrappers\Template;
	use stdClass;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();

		}

		/**
		 * All ajax events
		 * 
		 * @return string, echoed
		 */
		private function listen(){

			//creating a section:
			add_action( 'wp_ajax_autoload', array( &$this, 'handleAutoload' ) );
			add_action( 'wp_ajax_nopriv_autoload', array( &$this, 'handleAutoload' ) );

		}


		public function handleAutoload(){

			$this->setPostGlobal();

			$id = $_POST['column'];
			$section = $_POST['section'];

			//fetch the column
			$column = Column::collection( $id, $section );

			//set the new page-number:
			$column->setPage( $_POST['page'] );

			//then, check if there are posts:
			$q = $column->getQuery();
			if( !$q->have_posts() ){
				
				echo 'message';

			}else{
				
				//if there are posts, load the template:
				Template::column( $column, $section )->display();
			
			}

			die();
		}

	}


	\ChefSections\Front\Ajax::getInstance();
