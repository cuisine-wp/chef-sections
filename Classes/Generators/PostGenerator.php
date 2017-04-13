<?php
	
	namespace ChefSections\Generators;

	class PostGenerator{

		/**
		 * Attributes array
		 * 
		 * @var Array
		 */
		protected $attributes;

		/**
		 * Constructor
		 * 
		 * @param Array $attributes
		 *
		 * @return void
		 */
		public function __construct( $attributes )
		{
			$this->attributes = $attributes;	
		}

		/**
		 * Create the post out of the attributes
		 * 
		 * @return int (post ID)
		 */
		public function generate()
		{
			$attributes = $this->sanitizeAttributes();
			$postId = wp_insert_post( $attributes, true );

			if( is_wp_error( $postId ) )
				return null;
			

			return $postId;
		}


		/**
		 * Check the attributes for some defaults
		 * 
		 * @return array
		 */
		public function sanitizeAttributes()
		{
			$attributes = [];
			$props = $this->attributes;

			if( !isset( $props['title'] ) )
				$props['title'] = 'New generated post';

			if( !isset( $props['type'] ) )
				$props['type'] = 'post';

			if( !isset( $props['content'] ) )
				$props['content'] = '';

			if( !isset( $props['excerpt'] ) )
				$props['excerpt'] = '';

			if( !isset( $props['status'] ) )
				$props['status'] = 'publish';

			foreach( $props as $key => $prop ){
				if( substr( $key, 0, 5 ) != 'post_' ) 
					$attributes[ 'post_'.$key ] = $prop;
			}

			$attributes = wp_parse_args( $props, $attributes );
			return $attributes;
		}

	}