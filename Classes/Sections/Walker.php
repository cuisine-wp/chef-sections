<?php

	namespace ChefSections\Sections;

	use \ChefSections\Sections\SectionsBuilder;
	use \ChefSections\Wrappers\Template;

	class Walker extends SectionsBuilder{


		/**
		 * Walk through all sections, get templates
		 *
		 * @return string ( html )
		 */
		public function walk(){


			ob_start();

			foreach( $this->sections as $section ){

				Template::section( $section )->display();

			}


			return ob_get_clean();
		}



		/**
		 * Walk through all columns of this section & get templates
		 *
		 * @param  \ChefSections\Columns\Column
		 * @return string ( html )
		 */
		public function columns( $section ){

			ob_start();

			foreach( $section->columns as $column ){

				$column->beforeTemplate();

				Template::column( $column )->display();

				$column->afterTemplate();

			}

			return ob_get_clean();
		}

		/**
		 * Get a template for a collection-block
		 * 
		 * @param  \ChefSections\Columns\Column
		 * @return string ( html )
		 */
		public function block( $column ){

			Template::block( $column )->display();
		}


		/**
		 * Returns if this post has sections
		 *
		 * @return bool
		 */
		public function hasSections(){

			if( !empty( $this->sections ) )
				return true;

			return false;
		}



	}


	
