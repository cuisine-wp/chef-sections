<?php

	namespace ChefSections\Admin\Ui\Containers;

	use ChefSections\Helpers\SectionUi as SectionUiHelper;

	class GroupedUi{

		/**
		 * Container class
		 * 
		 * @var ChefSections\SectionTypes\
		 */
		protected $container;


		/**
		 * Constructor
		 * 
		 * @param ChefSections\SectionTypes\Container
		 *
		 * @return void
		 */
		public function __construct( $container )
		{
			$this->container = $container;
		}


		/**
		 * Builds the UI for a tabbed container
		 * 
		 * @return string (html, echoed)
		 */
		public function build()
		{			

			echo '<div class="section-sortables" data-container_id="'.$this->container->id.'">';

				if( !$this->container->sections->isEmpty() ){

					foreach( $this->container->sections->all() as $section ){

						SectionUiHelper::getClass( $section )->build();

					}
				}

			echo '</div>';
		}


	}