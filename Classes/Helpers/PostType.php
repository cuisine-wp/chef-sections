<?php

	namespace CuisineSections\Helpers;


	class PostType{

		public static function isValid( $post_id )
		{
			$post_types = array( 'page', 'section-template', 'page-template' );
			$post_types = apply_filters( 'chef_sections_post_types', $post_types );

			return in_array( get_post_type( $post_id ), $post_types );
		}

	}