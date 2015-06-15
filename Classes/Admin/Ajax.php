<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\AjaxInstance;
	use ChefSections\Wrappers\SectionsBuilder;
	use ChefSections\Wrappers\Column;
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

				$this->setPostGlobal();

				echo SectionsBuilder::addSection();
				die();

			});

			//delete section:
			add_action( 'wp_ajax_deleteSection', function(){

				$this->setPostGlobal();

				SectionsBuilder::deleteSection();
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

				$this->setPostGlobal();
				echo Column::saveProperties();
				die();

			});


			add_action( 'wp_ajax_refreshColumn', function(){

				$this->setPostGlobal();

				Column::refreshColumn();
				die();

			});

		}
	}


	if( is_admin() )
		\ChefSections\Admin\Ajax::getInstance();
