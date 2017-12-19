<?php


	namespace CuisineSections\Templates;


	class BaseSectionTemplate extends BaseTemplate{


		/**
		 * Get the protected type
		 * 
		 * @var string
		 */
		protected $type = 'section';


		/**
		 * Get the default base folder
		 * 
		 * @var string
		 */
		protected $baseFolder = 'sections/';


		/**
		 * Display the actual template
		 * 
		 * @return String (html, echoed)
		 */
		public function display()
		{
			$type = 'section';
			$section = $this->object;

			add_action( 'cuisine_sections_before_'.$this->type.'_template', $this->object );

			include( $this->located );

			add_action( 'cuisine_sections_after_'.$this->type.'_template', $this->object );		
		}


		/******************************************************/
		/**             Logic                                 */
		/******************************************************/


		/**
		 * Returns the default hierarchy for this template
		 * 
		 * @return Array
		 */
		public function getHierarchy()
		{
			$post = $this->getPost();
			$templates = [];

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

			$templates[] = $this->constructPath( $this->object->view );
			$templates[] = $this->constructPath( 'default' );

			$templates[] = $this->constructPath(
				'default'
			);

			$templates = $this->removeDuplicates( $templates );
			return apply_filters( 'cuisine_sections_section_template_hierarchy', $templates );;
		}


		/******************************************************/
		/**             Helper functions                      */
		/******************************************************/

		/**
		 * Returns the right post object
		 * 
		 * @return WP_Post
		 */
		public function getPost()
		{
			return apply_filters( 'cuisine_sections_template_post_getter', get_post( $this->object->post_id ) );
		}



	}