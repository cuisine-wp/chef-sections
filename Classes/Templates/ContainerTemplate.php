<?php

	namespace ChefSections\Templates;


	class ContainerTemplate extends BaseSectionTemplate{


		protected $baseFolder = 'containers/';


		/**
		 * Returns the default hierarchy for this template
		 * 
		 * @return Array
		 */
		public function getHierarchy()
		{
			//$templates = parent::getHierarchy();
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
				'container'
			);

			$templates = $this->removeDuplicates( $templates );
			$this->baseFolder = $base; //reset the base
			return apply_filters( 'chef_sections_container_template_hierarchy', $templates );;
		}


		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function default()
		{
			$base = $this->pluginPath();
			$default = $base.'Sections/container.php';
			$default = apply_filters( 'chef_sections_default_template', $default, $this->object );
			return $default;
		}

	}