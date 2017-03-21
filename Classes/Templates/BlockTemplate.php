<?php

	namespace ChefSections\Templates;

	class BlockTemplate extends BaseTemplate{


		/**
		 * Get the protected type
		 * 
		 * @var string
		 */
		protected $type = 'block';


		/**
		 * Get the default base folder
		 * 
		 * @var string
		 */
		protected $baseFolder = 'collections/blocks/';


		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function default()
		{
			$base = $this->pluginPath();
			$default = $base.'Columns/collection-block.php';
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
					get_post_type()
				),
				$this->constructPath(
					'block'
				)
			];

			return apply_filters( 'chef_sections_block_template_hierarchy', $templates );
		}


	}