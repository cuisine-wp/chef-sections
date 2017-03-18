<?php

	namespace ChefSections\Admin\Ui\Sections;


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

			$class = 'section-wrapper ui-state-default section-'.$this->section->id;

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->section->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.esc_attr( $this->section->view ).'">';
						
					//returns the sections registered with this group


					echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
		
	}