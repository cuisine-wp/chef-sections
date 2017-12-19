<?php

	namespace CuisineSections\Helpers;

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

					'name'		=> __( 'Textual content', 'CuisineSections' ),
					'class' 	=> 'CuisineSections\\Columns\\ContentColumn'
				),

				'image'			=> array(
					'name'		=> __( 'Image', 'CuisineSections' ),
					'class' 	=> 'CuisineSections\\Columns\\ImageColumn',			
				),

				'video'			=> array(

					'name'		=> __( 'Video', 'CuisineSections' ),
					'class' 	=> 'CuisineSections\\Columns\\VideoColumn',
				),

				'collection'	=> array(

					'name'		=> __( 'Collection', 'CuisineSections' ),
					'class'		=> 'CuisineSections\\Columns\\CollectionColumn'
				),

				'socials' 		=> array(
					'name'		=> __( 'Social buttons', 'CuisineSections' ),
					'class'		=> 'CuisineSections\\Columns\\SocialsColumn'
				),
			);


			$arr = apply_filters( 'cuisine_sections_column_types', $arr );
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