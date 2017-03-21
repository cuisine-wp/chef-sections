<?php

	namespace ChefSections\Templates;


	class ReferenceTemplate extends BaseSectionTemplate{


		protected $baseFolder = 'references/';

		/**
		 * Returns the right post object
		 * 
		 * @return WP_Post
		 */
		public function getPost()
		{
			return apply_filters( 'chef_sections_template_post_getter', get_post( $this->object->template_id ) );
		}

	}