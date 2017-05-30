<?php

	namespace ChefSections\Generators;

	use ChefSections\Contracts\Generator as GeneratorContract;

	class ContainerGenerator extends SectionGenerator implements GeneratorContract{

		/**
		 * All generated pages in an Array
		 * 
		 * @var Array
		 */
		protected $sections;

		/**
		 * All generated columns in an array
		 * 
		 * @var Array
		 */
		protected $columns;



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


		/**
		 * Add a section
		 *
		 * @param String $name
		 * @param Array $attributes
		 * 
		 * @return SectionGenerator
		 */
		public function section( $name, $attributes = array() )
		{
			$id = ( count( $this->attributes['sections'] ) + 1 );
			$props = [
				'id' => $id,
				'name' => $name,
				'position' => $id,
			];

			$section = new SectionGenerator( array_merge( $props, $attributes ) );
			$this->attributes['sections'][] = $section;
			return $section;
		}


	}