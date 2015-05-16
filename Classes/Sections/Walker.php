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

				Template::section( $section );

			}


			return ob_get_clean();
		}


		/**
		 * Walk through all columns of this section & get templates
		 * 
		 * @return string ( html )
		 */
		public function columns( $section ){

			ob_start();

			foreach( $section->columns as $column ){

				Template::column( $column );

			}

			return ob_get_clean();
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


	
