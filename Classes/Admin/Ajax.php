<?php

	namespace ChefSections\Admin;

	use ChefSections\Wrappers\AjaxInstance;
	use ChefSections\Sections\Manager as SectionManager;
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

				$manager = new SectionManager( $_POST['post_id'] );
				echo $manager->addSection();

				die();

			});

			//delete section:
			add_action( 'wp_ajax_deleteSection', function(){

				parent::setPostGlobal();

				$manager = new SectionManager( $_POST['post_id'] );
				$manager->deleteSection();

				SectionsBuilder::deleteSection();
				die();

			});

			//sorting sections:
			add_action( 'wp_ajax_sortSections', function(){

				parent::setPostGlobal();

				$manager = new SectionManager( $_POST['post_id'] );
				echo $manager->sortSections();

				die();

			});

			add_action( 'wp_ajax_sortColumns', function(){

				parent::setPostGlobal();

				$manager = new SectionManager( $_POST['post_id'] );
				echo $manager->sortColumns();

				die();
			});

			//change a sections' view:
			add_action( 'wp_ajax_changeView', function(){

				parent::setPostGlobal();
				
				$manager = new SectionManager( $_POST['post_id'] );
				echo $manager->changeView();

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
