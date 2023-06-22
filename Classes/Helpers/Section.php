<?php
	
	namespace ChefSections\Helpers;

	use ChefSections\SectionTypes\Container;
	use ChefSections\Collections\ContainerCollection;

	class Section{


		/**
		 * Returns the right class from raw section data
		 * 
		 * @param  Array $sectionData
		 * 
		 * @return ChefSections\Sections\BaseSection
		 */
		public static function getClass( $sectionData )
		{
			$classes = static::getClasses();

			switch( $sectionData['type'] ){
                case null:
                    return null;
                    break;
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
					
                    if( !is_null( $sectionData['type'] ) ){
                        $class = $classes[ $sectionData['type'] ];
                        
                        if( class_exists( $class ) ){
                            return new $class( $sectionData );
                        }
                    }

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
				'section' 			=> '\ChefSections\SectionTypes\ContentSection',
				'contentSection'	=> '\ChefSections\SectionTypes\ContentSection',
				'stencil'			=> '\ChefSections\SectionTypes\ContentSection',
				'reference'			=> '\ChefSections\SectionTypes\Reference',
				'blueprint'			=> '\ChefSections\SectionTypes\Blueprint',
				'container'			=> '\ChefSections\SectionTypes\Container'
			];

			return apply_filters( 'chef_sections_section_type_classes', $classes );
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

			$arr = apply_filters( 'chef_sections_section_types', $arr );
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
				'title'				=> __( 'Sectie titel', 'chefsections' ),
				'hide_title'		=> apply_filters('chef_sections_hide_title', false ),
				'hide_container'	=> apply_filters('chef_sections_hide_container', true ),
				'view'				=> 'fullwidth',
				'type'				=> 'section'
			);

			$args = apply_filters( 'chef_sections_default_section_args', $args );

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
				'title'				=> __( 'Sectie container title', 'chefsections' ),
				'hide_title'		=> apply_filters('chef_sections_hide_title', false ),
				'hide_container'	=> apply_filters('chef_sections_hide_container', true ),
				'view'				=> 'grouped',
				'type'				=> 'container'
			);

			$args = apply_filters( 'chef_sections_default_section_container_args', $args );
			return $args;	
		}


	}
