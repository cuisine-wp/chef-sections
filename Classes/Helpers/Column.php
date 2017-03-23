<?php

	namespace ChefSections\Helpers;

	class Column{


		/**
		 * Return all availabel column types
		 * 
		 * @return array
		 */
		public static function getAvailableTypes()
		{
			$arr = array(

				'content' 		=> array(

					'name'		=> __( 'Textual content', 'chefsections' ),
					'class' 	=> 'ChefSections\\Columns\\ContentColumn'
				),

				'image'			=> array(
					'name'		=> __( 'Image', 'chefsections' ),
					'class' 	=> 'ChefSections\\Columns\\ImageColumn',			
				),

				'video'			=> array(

					'name'		=> __( 'Video', 'chefsections' ),
					'class' 	=> 'ChefSections\\Columns\\VideoColumn',
				),

				'collection'	=> array(

					'name'		=> __( 'Collection', 'chefsections' ),
					'class'		=> 'ChefSections\\Columns\\CollectionColumn'
				),

				'socials' 		=> array(
					'name'		=> __( 'Social buttons', 'chefsections' ),
					'class'		=> 'ChefSections\\Columns\\SocialsColumn'
				),
			);


			$arr = apply_filters( 'chef_sections_column_types', $arr );
			return $arr;	
		}


		/**
		 * Check if a column-type exists:
		 * 
		 * @return bool
		 */
		public static function typeExists( $type )
		{

			$types = static::getAvailableTypes();
			return array_key_exists( $type, $types );

		}
		
	}