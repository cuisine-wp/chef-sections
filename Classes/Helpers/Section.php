<?php
	
	namespace ChefSections\Helpers;


	class Section{

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