<?php

	namespace ChefSections\SectionTypes;

	class Container extends BaseSection{

		/**
		 * Strint to detirmen which type of section this is
		 *
		 * @possible values: section - reference - stencil - container
		 * 
		 * @var string
		 */
		public $type = 'container';
			

	}