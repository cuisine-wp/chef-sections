<?php

	namespace ChefSections\Templates;

	class ElementTemplate extends BaseTemplate{


		/**
		 * Get the protected type
		 * 
		 * @var string
		 */
		protected $type = 'element';


		/**
		 * Get the default base folder
		 * 
		 * @var string
		 */
		protected $baseFolder = 'elements/';


		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function getDefault()
		{
			$base = $this->pluginPath();
			$default = $base.'Elements/'.$this->object.'.php';
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

			$templates = [
				$this->constructPath(
					$this->object
				)
			];

			return apply_filters( 'chef_sections_element_template_hierarchy', $templates );
		}


	}