w<?php

	namespace CuisineSections\Templates;


	class ReferenceTemplate extends BaseSectionTemplate{


		protected $baseFolder = 'references/';


		/**
		 * Returns the default hierarchy for this template
		 * 
		 * @return Array
		 */
		public function getHierarchy()
		{
			$templates = parent::getHierarchy();
			$post = $this->getPost();

			$base = $this->baseFolder;
			$this->baseFolder = 'sections/';

			if( !is_null( $this->object->name ) && $this->object->name != '' ){
				$templates[] = $this->constructPath(
					$post->post_name,
					$this->object->name
				);
			}

			$templates[] = $this->constructPath(
				$post->post_name,
				$this->object->view
			);

			if( !is_null( $this->object->name ) && $this->object->name != '' ){
				$templates[] = $this->constructPath(
					$this->object->name
				);
			}

			$templates[] = $this->constructPath(
				$this->object->view
			);

			$templates = $this->removeDuplicates( $templates );
			$this->baseFolder = $base; //reset the base
			return apply_filters( 'cuisine_sections_reference_template_hierarchy', $templates );;
		}

		/**
		 * Returns the right post object
		 * 
		 * @return WP_Post
		 */
		public function getPost()
		{
			return apply_filters( 'cuisine_sections_template_post_getter', get_post( $this->object->template_id ) );
		}

	}