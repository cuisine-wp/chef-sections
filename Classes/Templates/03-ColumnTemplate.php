<?php

	namespace ChefSections\Templates;

	use ChefSections\Helpers\Column as ColumnHelper;

	class ColumnTemplate extends BaseTemplate{

		/**
		 * Get the protected type
		 * 
		 * @var string
		 */
		protected $type = 'column';


		/**
		 * Get the default base folder
		 * 
		 * @var string
		 */
		protected $baseFolder = 'columns/';



		/**
		 * Display the actual template
		 * 
		 * @return String (html, echoed)
		 */
		public function display()
		{
			$type = 'column';
			$column = $this->object;

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

			$types = ColumnHelper::getAvailableTypes();
			$column = $types[ $this->object->type ];

			if( !isset( $column['template'] ) || $column['template'] == '' ){

				$base = $this->pluginPath();
				$default = $base.'Columns/'. $this->object->type. '.php';

			}else{
				$default = $column['template'];

			}
			
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
					$this->object->section->name,
					$this->object->id
				),

				$this->constructPath(
					$post->post_name,
					$this->object->section->name,
					$this->object->type
				),

				$this->constructPath(
					$post->post_name,
					$this->object->type
				),
			];

			//only add the following templates if the section name is set:
			if( !is_null( $this->object->section->name ) && $this->object->section->name != '' ){
				$templates = array_merge( $templates, [

					$this->constructPath(
						$this->object->section->name,
						$this->object->id
					),

					$this->constructPath(
						$this->object->section->name,
						$this->object->type
					)
				]);
			}

			//add just the objec
			$templates[] = $this->constructPath(
				$this->object->type
			);

			$templates = $this->removeDuplicates( $templates );
			return apply_filters( 'chef_sections_section_template_hierarchy', $templates );
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