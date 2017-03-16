<?php

	namespace ChefSections\Admin;

	use Cuisine\Wrappers\PostType;
	use ChefSections\Wrappers\Column;
	use ChefSections\Wrappers\Walker;
	use ChefSections\Wrappers\AjaxInstance;
	use ChefSections\Wrappers\ReferenceBuilder;
	use ChefSections\Admin\Managers\SectionManager;
	use ChefSections\Admin\Managers\TemplateManager;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->addSectionEvents();
			$this->addTemplateEvents();
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

				( new SectionManager() )->addSection();

				die();

			});

			//delete section:
			add_action( 'wp_ajax_deleteSection', function(){

				parent::setPostGlobal();

				( new SectionManager() )->deleteSection();

				die();
			});

			//sorting sections:
			add_action( 'wp_ajax_sortSections', function(){

				parent::setPostGlobal();

				( new SectionManager() )->sortSections();

				die();

			});

			add_action( 'wp_ajax_sortColumns', function(){

				parent::setPostGlobal();

				( new SectionManager() )->sortColumns();

				die();
			});

			//change a sections' view:
			add_action( 'wp_ajax_changeView', function(){

				parent::setPostGlobal();

				( new SectionManager() )->changeView();

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
		 * Set functions for templates
		 */
		private function addTemplateEvents()
		{
			add_action( 'wp_ajax_addSectionTemplate', function(){
				
				parent::setPostGlobal();

				( new TemplateManager() )->addReference();

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
