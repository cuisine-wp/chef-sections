<?php

	namespace ChefSections\Front;

	use \ChefSections\Sections\SectionsBuilder;
	use \ChefSections\Sections\Section;
	use \ChefSections\Wrappers\Template;
	use \stdClass;

	class Walker extends SectionsBuilder{


		/**
		 * Walk through all sections, get templates
		 *
		 * @return string ( html )
		 */
		public function walk(){


			ob_start();

			foreach( $this->sections as $section ){

				$section->beforeTemplate();

				Template::section( $section )->display();

				$section->afterTemplate();
			}


			return ob_get_clean();
		}

		/**
		* Get a single section
		*
		* @param int $post_id
		* @param int $section_id
		* @return string (html)
		*/
		public function get_section( $post_id, $section_id ){

			$template = false;
			$sections = get_post_meta( $post_id, 'sections', true );

			if( is_array( $sections ) ){
										
				foreach( $sections as $section ){
				
					//if this section in the loop matches 
					//the one we're looking for:		
					if( $section['id'] == $section_id ){

						$args = $section;

						//setup section object
						$section = new Section( $args );
						
						ob_start();
							//render it's template:
							Template::section( $section )->display();

						$template = ob_get_clean();

					}
				}
			}

			return $template;
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


	
