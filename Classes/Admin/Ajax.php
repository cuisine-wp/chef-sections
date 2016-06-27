<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\AjaxInstance;
	use ChefSections\Wrappers\SectionsBuilder;
	use ChefSections\Wrappers\ReferenceBuilder;
	use ChefSections\Wrappers\Column;
	use ChefSections\Wrappers\Walker;
	use Cuisine\Wrappers\PostType;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){


			$this->addSectionEvents();
			$this->addColumnEvents();

		}



		/**
		 * All ajax events for sections on the backend
		 * 
		 * @return string, echoed
		 */
		private function addSectionEvents(){


			//creating a section:
			add_action( 'wp_ajax_createSection', function(){

				parent::setPostGlobal();

				echo SectionsBuilder::addSection();
				die();

			});

			//delete section:
			add_action( 'wp_ajax_deleteSection', function(){

				parent::setPostGlobal();

				SectionsBuilder::deleteSection();
				die();

			});

			//sorting sections:
			add_action( 'wp_ajax_sortSections', function(){

				parent::setPostGlobal();

				echo SectionsBuilder::sortSections();
				die();

			});

			add_action( 'wp_ajax_sortColumns', function(){

				parent::setPostGlobal();

				echo SectionsBuilder::sortColumns();
				die();
			});

			//change a sections' view:
			add_action( 'wp_ajax_changeView', function(){

				parent::setPostGlobal();

				echo SectionsBuilder::changeView();
				die();

			});

			//load a template with ajax:
			add_action( 'wp_ajax_loadTemplate', function(){

				parent::setPostGlobal();

				ReferenceBuilder::addReference();
				die();

			});

			//fetch all html output
			add_action( 'wp_ajax_getHtmlOutput', function(){

				parent::setPostGlobal();
				echo Walker::walk();
				die();

			});

		}


		/**
		 * Alle ajax events for columns on the backend 
		 *
		 * @return string, echoed
		 */
		private function addColumnEvents(){

			add_action( 'wp_ajax_saveColumnType', function(){

				parent::setPostGlobal();

				Column::saveType();
				die();

			});



			//save lightbox data:
			add_action( 'wp_ajax_saveColumnProperties', function(){

				parent::setPostGlobal();
				
				echo Column::saveProperties();
				die();

			});


			add_action( 'wp_ajax_refreshColumn', function(){

				parent::setPostGlobal();

				Column::refreshColumn();
				die();

			});

		}
	}


	if( is_admin() )
		\ChefSections\Admin\Ajax::getInstance();
