<?php

	namespace ChefSections\Admin\Ui\Sections;

	use ChefSections\Helpers\SectionUi as SectionUiHelper;

	class ContainerSectionUi extends BaseSectionUi{

		/**
		 * The container object
		 * 
		 * @var mixed
		 */
		protected $container;

		/**
		 * Constructor
		 * 
		 * @param ChefSections\SectionTypes\BaseSection $section
		 *
		 * @return void
		 */
		public function __construct( $section )
		{
			$this->section = $section;
			$this->container = $section;
		}

		/**
		 * Build the UI for this container
		 * 
		 * @return String (html, echoed)
		 */
		public function build(){

			$class = 'section-wrapper ui-state-default section-container section-'.$this->container->id;

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->container->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns container-sections '.esc_attr( $this->container->view ).'">';
						
					echo '<div class="section-sortables" data-container_id="'.$this->container->id.'">';

						if( !$this->container->sections->empty() ){

							foreach( $this->container->sections->all() as $section ){

								SectionUiHelper::getClass( $section )->build();

							}
						}


					echo '</div>';

					echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}



		
	}