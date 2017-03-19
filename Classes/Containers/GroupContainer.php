<?php 
	
	namespace ChefSections\Containers;

	use ChefSections\SectionTypes\Container;


	class GroupContainer extends Container{

		/**
		 * Return a tabbed view
		 * 
		 * @return string
		 */
		public function getView()
		{
			return 'tabbed';
		}
	}