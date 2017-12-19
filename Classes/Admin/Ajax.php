<?php

	namespace CuisineSections\Admin;

	use Cuisine\Wrappers\PostType;
	use CuisineSections\Wrappers\Column;
	use CuisineSections\Wrappers\Walker;
	use CuisineSections\Wrappers\AjaxInstance;
	use CuisineSections\Wrappers\ReferenceBuilder;
	use CuisineSections\Admin\Handlers\ColumnHandler;
	use CuisineSections\Admin\Handlers\SectionHandler;
	use CuisineSections\Admin\Handlers\ContainerHandler;
	use CuisineSections\Admin\Handlers\SectionBlueprintHandler;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->addSectionEvents();
			$this->addTemplateEvents();
			$this->addContainerEvents();
			$this->addColumnEvents();

		}



		/**
		 * All ajax events for sections on the backend
		 * 
		 * @return void
		 */
		private function addSectionEvents(){


			//creating a section:
			add_action( 'wp_ajax_createSection', function(){

				parent::setPostGlobal();

				( new SectionHandler() )->addSection();

				die();

			});

			//delete section:
			add_action( 'wp_ajax_deleteSection', function(){

				parent::setPostGlobal();

				( new SectionHandler() )->deleteSection();

				die();
			});

			//sorting sections:
			add_action( 'wp_ajax_sortSections', function(){

				parent::setPostGlobal();

				( new SectionHandler() )->sortSections();

				die();

			});

			add_action( 'wp_ajax_sortColumns', function(){

				parent::setPostGlobal();

				( new SectionHandler() )->sortColumns();

				die();
			});

			//change a sections' view:
			add_action( 'wp_ajax_changeView', function(){

				parent::setPostGlobal();

				( new SectionHandler() )->changeView();

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
		 *
		 * @return void
		 */
		private function addTemplateEvents()
		{
			add_action( 'wp_ajax_addSectionTemplate', function(){
				
				parent::setPostGlobal();

				( new SectionBlueprintHandler() )->addSectionBlueprint();

				die();

			});
		}

		/**
		 * Set functions for containers
		 *
		 * @return void
		 */
		private function addContainerEvents()
		{
			add_action( 'wp_ajax_addSectionContainer', function(){

				parent::setPostGlobal();

				( new ContainerHandler() )->addContainer();

				die();

			});
		}


		/**
		 * Alle ajax events for columns on the backend 
		 *
		 * @return void
		 */
		private function addColumnEvents(){

			add_action( 'wp_ajax_saveColumnType', function(){

				parent::setPostGlobal();

				( new ColumnHandler() )->saveType();
				die();

			});



			//save lightbox data:
			add_action( 'wp_ajax_saveColumnProperties', function(){

				parent::setPostGlobal();
				
				echo ( new ColumnHandler() )->saveProperties();
				die();

			});


			add_action( 'wp_ajax_refreshColumn', function(){

				parent::setPostGlobal();

				( new ColumnHandler() )->refreshColumn();
				die();

			});

		}
	}
