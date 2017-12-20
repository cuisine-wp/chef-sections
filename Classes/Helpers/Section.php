<?php
	
	namespace CuisineSections\Helpers;

	use CuisineSections\SectionTypes\Container;
	use CuisineSections\Collections\ContainerCollection;

	class Section{


		/**
		 * Returns the right class from raw section data
		 * 
		 * @param  Array $sectionData
		 * 
		 * @return CuisineSections\Sections\BaseSection
		 */
		public static function getClass( $sectionData )
		{
			$classes = static::getClasses();

			switch( $sectionData['type'] ){

				case 'container':

					//check if there's a container slug set:
					if( isset( $sectionData['slug'] ) && $sectionData['slug'] != '' ){

						$container = ( new ContainerCollection() )->get( $sectionData['slug'] );
						$class = $container['class'];
						return new $class( $sectionData );
					}

					return new Container( $sectionData );
					break;

				default:
                    
                    $class = $classes[ $sectionData['type'] ];
					return new $class( $sectionData );
					break;
			}
		}

		/**
		 * Returns an array of all Section Type classes (filterable)
		 * 
		 * @return Array
		 */
		public static function getClasses(){

			$classes = [
				'section' 			=> '\CuisineSections\SectionTypes\ContentSection',
				'contentSection'	=> '\CuisineSections\SectionTypes\ContentSection',
				'stencil'			=> '\CuisineSections\SectionTypes\ContentSection',
				'reference'			=> '\CuisineSections\SectionTypes\Reference',
				'blueprint'			=> '\CuisineSections\SectionTypes\Blueprint',
				'container'			=> '\CuisineSections\SectionTypes\Container'
			];

			return apply_filters( 'cuisine_sections_section_type_classes', $classes );
		}

		/**
		 * Returns all possible Section view types
		 * 
		 * @return array
		 */
		public static function viewTypes()
		{
			//name - column count
			$arr = array(
				'fullwidth' => 1,
				'half-half' => 2,
				'sidebar-left' => 2,
				'sidebar-right' => 2,
				'three-columns' => 3,
				'four-columns' => 4
			);

			$arr = apply_filters( 'cuisine_sections_section_types', $arr );
			return $arr;
		}


		/**
		 * Default Section arguments
		 * 
		 * @return array
		 */
		public static function defaultArgs()
		{

			$args = array(
				'title'				=> __( 'Section title', 'CuisineSections' ),
				'hide_title'		=> apply_filters('cuisine_sections_hide_title', false ),
				'hide_container'	=> apply_filters('cuisine_sections_hide_container', true ),
				'view'				=> 'fullwidth',
				'type'				=> 'section'
			);

			$args = apply_filters( 'cuisine_sections_default_section_args', $args );

			return $args;	
		}


		/**
		 * Default Container args
		 * 
		 * @return array
		 */
		public static function defaultContainerArgs()
		{

			$args = array(
				'title'				=> __( 'Section container title', 'CuisineSections' ),
				'hide_title'		=> apply_filters('cuisine_sections_hide_title', false ),
				'hide_container'	=> apply_filters('cuisine_sections_hide_container', true ),
				'view'				=> 'grouped',
				'type'				=> 'container'
			);

			$args = apply_filters( 'cuisine_sections_default_section_container_args', $args );
			return $args;	
		}


	}