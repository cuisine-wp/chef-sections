<?php

	namespace ChefSections\Generators;

	use ChefSections\Contracts\Generator as GeneratorContract;

	class ContainerGenerator extends SectionGenerator implements GeneratorContract{


		/**
		 * Build this object into a section (and it's corresponding post)
		 * 
		 * @return void
		 */
		public function generate( $meta = array() )
		{
			$meta = parent::generate( $meta );

			foreach( $this->get( 'sections', array() ) as $section ){

				$meta = $section->postId( $this->postId )
								->containerId( $this->id )
								->generate( $meta );
			}

			return $meta;
		}

		/**
		 * Sanitize the attributes of this section
		 * 
		 * @param  Array $attributes
		 * 
		 * @return Array
		 */
		public function sanitizeAttributes( $attributes, $meta )
		{
			$attributes = parent::sanitizeAttributes( $attributes, $meta );
			$attributes['columns'] = [];
			unset( $attributes[ 'sections' ] );
			return $attributes;
		}

	}