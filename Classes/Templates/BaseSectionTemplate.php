<?php


	namespace ChefSections\Templates;


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

			add_action( 'chef_sections_before_'.$this->type.'_template', $this->object );

			include( $this->located );

			add_action( 'chef_sections_after_'.$this->type.'_template', $this->object );		
		}


		/******************************************************/
		/**             Logic                                 */
		/******************************************************/

		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function default()
		{
			$base = $this->pluginPath();
			$default = $base.'Sections/default.php';
			$default = apply_filters( 'chef_sections_default_template', $default, $this->object );
			return $default;
		}


		/**
		 * Returns the default hierarchy for this template
		 * 
		 * @return Array
		 */
		public function getHierarchy()
		{
			$post = $this->getPost();

			$templates = [
				$this->constructPath(
					$post->post_name,
					$this->object->name
				),

				$this->constructPath(
					$post->post_name,
					$this->object->view
				),

				$this->constructPath(
					$this->object->view
				)
			];

			return apply_filters( 'chef_sections_section_template_hierarchy', $templates );;
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
			return apply_filters( 'chef_sections_template_post_getter', get_post( $this->object->post_id ) );
		}



	}